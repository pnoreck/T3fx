<?php
/*
 * Copyright 2017 - Steffen Hastädt
 *
 * T3Fx Application Development
 *
 * info@t3x.ch | www.t3x.ch
 */

namespace T3fx\Application\MailScanner\Domain\Repository;

/**
 * Class SenderRepository
 *
 * @package T3fx\Application\MailScanner\Domain\Repository
 */
class SenderRepository extends AbstractRepository
{

    /**
     * @param $sender
     *
     * @return mixed
     */
    public function getByName($sender)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('uid, pid, name, imap_folder');
        $query->from($this->getTableName());
        $query->where('name = ?' . self::HIDDEN_FIELDS);
        $query->setParameter(0, $sender);

        return $query->execute()->fetch();
    }


    /**
     * Create a new sender without folder connection
     *
     * @param $email
     *
     * @return bool
     */
    public function createUndefinedSender($email)
    {
        $sender = $this->getByName($email);
        if (is_array($sender)) {
            return false;
        }

        $this->insertArray(
            $this->getTableName(),
            [
                'name'   => $email,
                'pid'    => 46,
                'tstamp' => time(),
                'crdate' => time(),
            ]
        );
    }
}
