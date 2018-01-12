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
 * Class ImapfolderRepository
 *
 * @package T3fx\Application\MailScanner\Domain\Repository
 */
class ImapfolderRepository extends AbstractRepository
{

    /**
     * @param $folderUid
     *
     * @return mixed
     */
    public function getByUid($folderUid)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('full_name');
        $query->from($this->getTableName());
        $query->where('uid = ?' . self::HIDDEN_FIELDS);
        $query->setParameter(0, $folderUid);

        return $query->execute()->fetch();
    }

    /**
     * Find folder by full folder name
     *
     * @param string $fullNname
     * @param bool   $ignoreHiddenFields
     *
     * @return mixed
     */
    public function getByName($fullNname, $ignoreHiddenFields = false)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*');
        $query->from($this->getTableName());
        $query->where('full_name = ?' . (($ignoreHiddenFields) ? '' : self::HIDDEN_FIELDS));
        $query->setParameter(0, $fullNname);

        return $query->execute()->fetch();
    }

}
