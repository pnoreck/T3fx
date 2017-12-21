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
 * Class BlackListRepository
 *
 * @package T3fx\Application\MailScanner\Domain\Repository
 */
class BlackListRepository extends AbstractRepository
{

    /**
     * @param string $mailFrom
     * @param string $domain
     *
     * @return array|false
     */
    public function findBlacklistEntry($mailFrom, $domain)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('uid, pid, mail, domain');
        $query->from($this->getTableName());
        $query->where('mail = ? OR domain = ?' . self::HIDDEN_FIELDS);
        $query->setParameter(0, $mailFrom);
        $query->setParameter(1, $domain);

        return $query->execute()->fetch();
    }


}
