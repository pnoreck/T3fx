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
 * Class BlacklistRepository
 *
 * @package T3fx\Application\MailScanner\Domain\Repository
 */
class BlacklistRepository extends AbstractRepository
{

    /**
     * Find a blacklist entry with the given email address or domain
     *
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

        return $query->execute()->fetchAssociative();
    }

    /**
     * Add a whole domain to the blacklist
     *
     * @param $domain
     *
     * @return void
     */
    public function addDomainToBlacklist($domain)
    {
        $this->insert(
            [
                'domain' => $domain,
                'mail'   => '',
            ]
        );
    }

    /**
     * Add only an email address to the blacklist
     *
     * @param $sender
     *
     * @return void
     */
    public function addSenderToBlacklist($sender)
    {
        $this->insert(
            [
                'domain' => '',
                'mail'   => $sender,
            ]
        );
    }


}
