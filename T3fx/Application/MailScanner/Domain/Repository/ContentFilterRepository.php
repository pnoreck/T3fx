<?php
/*
 * Copyright 2018 - Steffen Hastädt
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
class ContentFilterRepository extends AbstractRepository
{

    public function findSubjectBySha1($sha1)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('uid, pid, sha1, content');
        $query->from($this->getTableName());
        $query->where('sha1 = ? AND filter_type = ?' . self::HIDDEN_FIELDS);
        $query->setParameter(0, $sha1);
        $query->setParameter(1, 'SUBJECT');

        return $query->execute()->fetch();
    }

    /**
     * Add subject to blacklist content filter
     *
     * @param string $content
     * @param string $sha1
     *
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    public function addSubject($content, $sha1)
    {
        return $this->insert(
            array(
                'content' => $content,
                'sha1'    => $sha1,
                'regex' => '',
            )
        );
    }

}
