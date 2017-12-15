<?php
/*
 * Copyright 2017 - Steffen Hastädt
 *
 * T3Fx Application Development
 *
 * info@t3x.ch | www.t3x.ch
 */

namespace T3fx\Application\MailScanner\Controller;

/**
 * Class MailScannerController
 *
 * @package T3fx\Application\MailScanner\Controller
 */
class MailScannerController
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
     * @param $sender
     *
     * @return bool|mixed
     */
    public function findBySender($sender)
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

}
