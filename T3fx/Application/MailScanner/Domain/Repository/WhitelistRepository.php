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
 * Class WhitelistRepository
 *
 * @package T3fx\Application\MailScanner\Domain\Repository
 */
class WhitelistRepository extends AbstractRepository
{

    /**
     * @param string $mailFrom
     * @param string $domain
     *
     * @return array|false
     */
    public function findByName($domain)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('uid, pid, name');
        $query->from($this->getTableName());
        $query->where('name = ?' . self::HIDDEN_FIELDS);
        $query->setParameter(0, $domain);

        return $query->execute()->fetchAssociative();
    }


}
