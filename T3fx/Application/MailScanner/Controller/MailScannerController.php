<?php
/*
 * Copyright 2017 - Steffen Hastädt
 *
 * T3Fx Application Development
 *
 * info@t3x.ch | www.t3x.ch
 */

namespace T3fx\Application\MailScanner\Controller;

use T3fx\Application\MailScanner\Domain\Repository\BlacklistRepository;
use T3fx\Application\MailScanner\Domain\Repository\ImapFolderRepository;
use T3fx\Application\MailScanner\Domain\Repository\SenderRepository;
use T3fx\Application\MailScanner\Utility\BlackListUtility;
use T3fx\Core\Controller\AbstractActionController;

/**
 * Class MailScannerController
 *
 * @package T3fx\Application\MailScanner\Controller
 */
class MailScannerController extends AbstractActionController
{

    /**
     * senderRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\SenderRepository
     */
    var $senderRepository;

    /**
     * imapFolderRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\ImapFolderRepository
     */
    var $imapFolderRepository;

    /**
     * BlacklistRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\BlacklistRepository
     */
    var $blacklistRepository;

    /**
     * @var \T3fx\Imap\Mailbox
     */
    var $mailbox;

    /**
     * Scanner constructor.
     */
    public function __construct()
    {
        /** @var \T3fx\Config $config */
        $config    = \T3fx\Config::getInstance();
        $mailboxes = $config->getApplicationConfig('MailScanner', 'MailBoxes');

        // TODO: We only scan one mailbox at the moment
        $mailbox = reset($mailboxes);

        $this->mailbox = new \T3fx\Library\Connector\Imap\Mailbox(
            '{' . $mailbox["host"] . ':993/imap/ssl}INBOX',
            $mailbox["user"],
            $mailbox["password"],
            __DIR__
        );
    }

    /**
     *
     *
     * @return void
     */
    protected function initRepositories()
    {
        $this->senderRepository     = new SenderRepository();
        $this->imapFolderRepository = new ImapFolderRepository();
        $this->blacklistRepository  = new BlacklistRepository();
    }

    /**
     *
     *
     * @return void
     */
    public function scanAction()
    {

        $mailsIds = $this->getMailIDs();
        foreach ($mailsIds as $mailId) {
            $mail = $this->mailbox->getMail($mailId, false);

            $folder = $this->findFolderBySender($mail->fromAddress);
            if (is_string($folder) && !empty($folder)) {
                $this->mailbox->moveMailToFolder($mailId, $folder);
                continue;
            }

            if ($this->checkAgainstPrivateBlacklist($mail->fromAddress)) {
                $this->mailbox->moveMailToFolder($mailId, 'INBOX/Junk');
                continue;
            }

            if ($this->checkAgainstPublicBlacklist($mailId)) {
                $this->mailbox->moveMailToFolder($mailId, 'INBOX/Junk');
                continue;
            }


            $this->database->createUndefinedSender($mail->fromAddress);
        }
    }


    /**
     * Return all Mail IDs from the inbox or die
     *
     * @return array
     */
    protected function getMailIDs()
    {
        // Read all messaged into an array:
        $mailsIds = $this->mailbox->searchMailbox('ALL');
        if (!$mailsIds) {
            die('empty');
        }

        // If we found mails we need the repositories
        $this->initRepositories();

        return $mailsIds;
    }

    /**
     * Find a imap folder by searching with the sender email address
     *
     * @param string $sender
     *
     * @return bool|mixed
     */
    public function findFolderBySender($sender)
    {

        $res = $this->senderRepository->getByName($sender);
        if (!is_array($res) || !isset($res["imap_folder"])) {
            return false;
        }

        $res = $this->imapFolderRepository->getByUid($res["imap_folder"]);
        if (is_array($res) && isset($res["full_name"])) {
            return preg_replace('/\{[^\}]+\}/i', '', $res["full_name"]);
        }

        return false;
    }

    /**
     * Find a blacklist entry in the local blacklist database
     *
     * @param string $mailFrom
     *
     * @return bool
     */
    public function checkAgainstPrivateBlacklist($mailFrom)
    {
        $explodedMail = explode('@', $mailFrom);
        $record       = $this->blacklistRepository->findBlacklistEntry($mailFrom, $explodedMail[1]);

        if (is_array($record) && !empty($record)) {
            return true;
        }

        return false;

    }

    protected function checkAgainstPublicBlacklist($mailId)
    {
        /** @var BlackListUtility $blacklistUtility */
        $blacklistUtility = BlackListUtility::getInstance();
        $mailHeader       = $this->mailbox->getImapHeaderInfo($mailId);
        return $blacklistUtility->checkAgainstPublicBlacklist($mailHeader);
    }

}
