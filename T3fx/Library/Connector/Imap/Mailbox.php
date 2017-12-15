<?php

namespace T3fx\Library\Connector\Imap;

class Mailbox extends \PhpImap\Mailbox
{

    /**
     * @return mixed
     */
    public function getMailBoxPath()
    {
        return $this->imapPath;
    }

    /**
     * @return array
     */
    public function getMailboxFolders()
    {
        return imap_list($this->getImapStream(), $this->imapPath, "*");
    }

    /**
     * @return array
     */
    public function getAllMailHeader()
    {
        return imap_headers($this->getImapStream());
    }

    /**
     * @param $mailId
     *
     * @return object
     */
    public function getImapHeaderInfo($mailId)
    {
        $imapHeader = imap_fetchheader($this->getImapStream(), $mailId, FT_UID);

        $parsed  = array();
        $blocks  = preg_split('/\n\n/', $imapHeader);
        $matches = array();
        foreach ($blocks as $i => $block) {
            $parsed[$i] = array();
            $lines      = preg_split(
                '/\n(([\w.-]+)\: *((.*\n\s+.+)+|(.*(?:\n))|(.*))?)/',
                $block,
                -1,
                PREG_SPLIT_DELIM_CAPTURE
            );
            foreach ($lines as $line) {
                if (preg_match(
                    '/^\n?([\w.-]+)\: *((.*\n\s+.+)+|(.*(?:\n))|(.*))?$/',
                    $line,
                    $matches
                )) {
                    $parsed[$i][$matches[1]] = preg_replace(
                        '/\n +/',
                        ' ',
                        trim($matches[2])
                    );
                }
            }
        }

        return $parsed;
    }

    /**
     * @param        $mail_id
     * @param string $folder
     */
    public function moveMailToFolder($mail_id, $folder = 'INBOX/Junk')
    {
        $imapresult = imap_mail_move($this->getImapStream(), $mail_id, $folder, CP_UID);
        if (!$imapresult) {
            echo imap_last_error();
        }
    }

    /**
     * @param $uid
     *
     * @return int
     */
    public function getMessageNumber($uid)
    {
        return imap_msgno($this->getImapStream(), $uid);
    }

}
