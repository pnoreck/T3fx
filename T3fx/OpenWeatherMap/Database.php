<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 21:29
 */

namespace T3fx\OpenWeatherMap;

class Database extends \T3fx\Library\Database\Doctrine\DBAL {


	public function getLatestWeather($cityUid) {
		$query = $this->conn->createQueryBuilder();
		$query->select('uid, crdate, city_id, json');
		$query->from('t3fx_weather');
		$query->where('city_id = ?');
		$query->setParameter(0, $cityUid);
		$query->addOrderBy('crdate', 'DESC');
		$query->setMaxResults(1);
		return $query->execute()->fetchAssociative();
	}

}
