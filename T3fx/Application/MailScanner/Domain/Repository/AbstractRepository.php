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
class AbstractRepository extends \T3fx\Domain\Repository\StandardRepository
{

    /**
     * Default hidden fields
     */
    const HIDDEN_FIELDS = ' AND deleted = 0 AND hidden = 0';

    /**
     * Default update method
     *
     * @param array  $what
     * @param string $where
     *
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    protected function update($what, $where)
    {
        return parent::updateTable(
            $this->getTableName(),
            array_merge(
                [
                    'tstamp' => time()
                ],
                $what
            ),
            $where
        );
    }

    public function insert($array)
    {
        return parent::insertArray(
            $this->getTableName(),
            array_merge(
                [
                    'pid'       => 46,
                    'cruser_id' => 0,
                    'hidden'    => 0,
                    'deleted'   => 0,
                    'tstamp'    => time(),
                    'crdate'    => time(),
                    'starttime' => 0,
                    'endtime'   => 0,
                ],
                $array
            )
        );
    }

}
