<?php
/*
 * Copyright 2017 - Steffen Hastädt
 *
 * T3Fx Application Development
 *
 * info@t3x.ch | www.t3x.ch
 */

namespace T3fx\Application\MailScanner\Controller;

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
     * imapfolderRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\ImapfolderRepository
     */
    var $imapfolderRepository;

    /**
     * BlacklistRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\BlacklistRepository
     */
    var $blacklistRepository;

    /**
     * ContentFilterRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\ContentFilterRepository
     */
    var $contentFilterRepository;

    /**
     * WhitelistRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\WhitelistRepository
     */
    var $whitelistRepository;


    /**
     * @var \T3fx\Imap\Mailbox
     */
    var $mailbox;

    /**
     * @var string
     */
    var $spamFolder = 'INBOX/Junk';

    /**
     * Scanner constructor.
     */
    public function __construct()
    {
    }

    /**
     * Initialize the required repositories
     *
     * @return void
     */
    protected function initRepositories($repositories = ['sender', 'imapfolder', 'blacklist', 'contentFilter'])
    {
        foreach ($repositories as $repository) {
            $varname = $repository . 'Repository';
            if (property_exists($this, $varname) && !$this->$varname) {
                $className      = '\\T3fx\\Application\\MailScanner\\Domain\\Repository\\' . ucfirst($varname);
                $this->$varname = new  $className();
            }
        }
    }

    /**
     *
     *
     * @return void
     */
    public function scanAction()
    {
        /** @var \T3fx\Config $config */
        $config    = \T3fx\Config::getInstance();
        $mailboxes = $config->getApplicationConfig('MailScanner', 'MailBoxes');

        // TODO: We only scan one mailbox at the moment
        $mailbox = reset($mailboxes);

        $this->mailbox    = new \T3fx\Library\Connector\Imap\Mailbox(
            '{' . $mailbox["host"] . ':993/imap/ssl}' . $mailbox["folder"],
            $mailbox["user"],
            $mailbox["password"],
            TEMP_FOLDER
        );
        $this->spamFolder = $mailbox["target"];
        $this->sortMails();
        $this->scanSpamBoxAction();
    }

    /**
     * Scan the mailbox for mails, chack for spam and sort the rest
     *
     * @return void
     */
    protected function sortMails()
    {
        $mailIds = $this->getMailIDs();
        if (!is_array($mailIds) || !$mailIds) {
            return false;
        }
        foreach ($mailIds as $mailId) {

            // Get the mail
            $mail = $this->mailbox->getMail($mailId, false);

            // Check if the mail is a "good" one and we have to move it in a folder
            $folder = $this->findFolderBySender($mail->fromAddress);
            if (is_string($folder) && !empty($folder)) {
                $this->mailbox->moveMailToFolder($mailId, $folder);
                continue;
            }

            // Check against a public blacklist if it's spam
            if ($this->checkAgainstPrivateBlacklist($mail->fromAddress)) {
                $this->mailbox->moveMailToFolder($mailId, $this->spamFolder);
                $this->checkForContentFilter($mail);
                continue;
            }

            // Check against our local playlist if it's spam
            if ($this->checkAgainstPublicBlacklist($mailId)) {
                $this->mailbox->moveMailToFolder($mailId, $this->spamFolder);
                $this->checkForContentFilter($mail);
                continue;
            }

            // Check against our local content filter if we marked it as spam
            if ($this->checkAgainstContentFilter($mail)) {
                $this->checkForSenderBlacklist($mail->fromAddress);
                $this->mailbox->moveMailToFolder($mailId, $this->spamFolder);
            }

            // We have an unidentified sender and we think it's not spam. That's why we make
            // an entry in the sender list so that the user can assign it to an folder
            $this->senderRepository->createUndefinedSender($mail->fromAddress);
        }

        $this->mailbox->disconnect();
    }


    public function scanSpamBoxAction()
    {
        /** @var \T3fx\Config $config */
        $config    = \T3fx\Config::getInstance();
        $mailboxes = $config->getApplicationConfig('MailScanner', 'SpamBoxes');
        foreach ($mailboxes as $mailbox) {
            $this->mailbox    = new \T3fx\Library\Connector\Imap\Mailbox(
                '{' . $mailbox["host"] . ':993/imap/ssl}' . $mailbox["folder"],
                $mailbox["user"],
                $mailbox["password"],
                TEMP_FOLDER
            );
            $this->spamFolder = $mailbox["target"];
            $this->scanSpam();
            $this->mailbox->disconnect();
        }
    }

    /**
     * The current mailbox is a spam folder. We analyse all mails and fill the blacklists with the content
     *
     * @return bool
     */
    protected function scanSpam()
    {
        $mailsIds = $this->getMailIDs();
        if (!$mailsIds) {
            return false;
        }

        foreach ($mailsIds as $mailId) {
            // Get the mail
            $mail = $this->mailbox->getMail($mailId, false);
            $this->checkForContentFilter($mail);
            $this->checkForSenderBlacklist($mail->fromAddress);
            $this->mailbox->moveMailToFolder($mailId, $this->spamFolder);
        }
    }


    /**
     * Return all Mail IDs from the inbox or die
     *
     * @return array|bool
     */
    protected function getMailIDs()
    {
        // Read all messaged into an array:
        $mailsIds = $this->mailbox->searchMailbox('ALL');
        if (!$mailsIds) {
            $this->mailbox->disconnect();
            return false;
        }

        // If we found mails we need the repositories
        $this->initRepositories(['sender', 'imapfolder', 'blacklist']);

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

        $res = $this->imapfolderRepository->getByUid($res["imap_folder"]);
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

    /**
     * Check the mail with the given mail ID against the configured DNSBLs
     *
     * @param int $mailId
     *
     * @return bool
     */
    protected function checkAgainstPublicBlacklist($mailId)
    {
        /** @var BlackListUtility $blacklistUtility */
        $blacklistUtility = BlackListUtility::getInstance();
        $mailHeader       = $this->mailbox->getImapHeaderInfo($mailId);
        return $blacklistUtility->checkAgainstPublicBlacklist($mailHeader);
    }


    /**
     * Check if the header of the given mail is already in the content filter otherwise add it
     *
     * @param \PhpImap\IncomingMail $mail
     *
     * @return void
     */
    protected function checkForContentFilter($mail)
    {
        $this->initRepositories(['contentFilter']);
        $subject = $mail->subject;
        $sha1    = sha1($subject);

        // We remove weird icons from the string which could make problems in the database
        $subject = preg_replace('/[^\p{L}\p{N}\s]/u', '', $subject);

        if (!$this->contentFilterRepository->findSubjectBySha1($sha1)) {
            $this->contentFilterRepository->addSubject($subject, $sha1);
        }
    }

    /**
     * Check the given mail if it has a match with the content filter
     *
     * @param \PhpImap\IncomingMail $mail
     *
     * @return bool
     */
    protected function checkAgainstContentFilter($mail)
    {
        $this->initRepositories(['contentFilter']);
        $subject = $mail->subject;
        $sha1    = sha1($subject);
        $result  = $this->contentFilterRepository->findSubjectBySha1($sha1);

        return (is_array($result) && $result);
    }

    /**
     * Check if we have to add the sender to the blacklist
     *
     * @param $sender
     *
     * @return void
     */
    protected function checkForSenderBlacklist($sender)
    {
        $this->initRepositories(['blacklist', 'whitelist']);
        $domain = explode('@', $sender);
        $domain = end($domain);
        if (!$this->blacklistRepository->findBlacklistEntry($sender, $domain)) {
            if (!$this->whitelistRepository->findByName($domain)) {
                $this->blacklistRepository->addDomainToBlacklist($domain);
            } else {
                $this->blacklistRepository->addSenderToBlacklist($domain);
            }
        }
    }

}
