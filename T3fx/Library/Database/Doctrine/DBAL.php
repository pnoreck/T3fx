<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 14:54
 */

namespace T3fx\Library\Database\Doctrine;

class DBAL {


	/**
	 * @var \Doctrine\DBAL\Query\QueryBuilder
	 */
	protected $conn;

	/**
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function __construct () {

		$config = new \Doctrine\DBAL\Configuration();
		$connectionParams = [
			'dbname'   => '',
			'user'     => '',
			'password' => '',
			'host'     => '',
			'driver'   => '',
		];

		include( DOCUMENT_ROOT . 'config.php' );
		$connectionParams = $connectionParams["database"];

		$this->conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
	}


	/**
	 * Insert array into table
	 *
	 * @param $table       string
	 * @param $insertArray array
	 */
	public function insertArray ($table, $insertArray) {
		$query = $this->conn->createQueryBuilder();
		$query->insert($table);
		$i = 0;
		foreach($insertArray as $name => $value) {
			$query->setValue($name, '?');
			$query->setParameter($i, $value);
			$i++;
		}
		$query->execute();
	}

	/**
	 * @param string $table
	 * @param array  $what
	 * @param string $where
	 *
	 * @return \Doctrine\DBAL\Driver\Statement|int
	 */
	protected function updateTable ($table, $what, $where) {
		$query = $this->conn->createQueryBuilder();
		$query->update($table);
		foreach($what AS $field => $value) {
			$query->set($field, $value);
		}
		$query->where($where);

		return $query->execute();
	}


}