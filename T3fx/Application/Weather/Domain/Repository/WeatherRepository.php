<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 09/07/16
 * Time: 10:10
 */

namespace T3fx\Application\Weather\Domain\Repository;

class WeatherRepository extends \T3fx\Domain\Repository\StandardRepository
{

    /**
     *
     *
     * @param $time
     *
     * @return mixed
     */
    public function getWeatherForTime($time)
    {

        if (preg_match('/^[0-9]+$/i', $time)) {
            $time = (int)$time;
        } else {
            $time = strtotime($time);
        }

        $query = $this->getSelectQuery();
        $query->where('crdate <= ?');
        $query->setParameter(0, $time);
        $query->orderBy('crdate', 'DESC');
        $query->setMaxResults(1);

        return $query->execute()->fetch();
    }
}