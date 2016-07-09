<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 09/07/16
 * Time: 10:56
 */

namespace T3fx\Domain\Repository;

class StandardRepository extends \T3fx\Library\Database\Doctrine\DBAL {
	/**
	 * @var string $tableName
	 */
	private $tableName;

	/**
	 * Returns the table name of the repository. If not is the it will be generated out of the class name
	 *
	 * @return string
	 */
	protected function getTableName() {
		if($this->tableName !== null)
			return $this->tableName;

		$className = (new \ReflectionClass($this))->getShortName();
		$this->tableName = strtolower('t3fx_'.preg_replace('/Repository$/', '', $className));
		return $this->tableName;
	}

	/**
	 * StandardRepository constructor.
	 */
	public function __construct () {
		parent::__construct();
		// $this->getTableName();
	}

	/**
	 * @return \Doctrine\DBAL\Query\QueryBuilder
	 */
	protected function getQuery() {
		return $this->conn->createQueryBuilder();
	}

	/**
	 * TODO: Does it make sense like this?
	 *
	 * @return \Doctrine\DBAL\Query\QueryBuilder
	 */
	protected function getSelectQuery() {
		$query = $this->conn->createQueryBuilder();
		$query->select('*');
		$query->from($this->getTableName());

		return $query;
	}

	/**
	 * Returns an insert query object
	 *
	 * @return \Doctrine\DBAL\Query\QueryBuilder
	 */
	protected function getInsertQuery() {
		$query = $this->conn->createQueryBuilder();
		$query->insert($this->getTableName());

		return $query;
	}

	/**
	 * Select record by UID
	 *
	 * @param $uid
	 *
	 * @return mixed
	 */
	public function getByUid($uid) {
		$query = $this->conn->createQueryBuilder();

		$query->select('*');
		$query->from($this->getTableName());

		$query->where('uid = ?');
		$query->setParameter(0, $uid);

		return $query->execute()->fetch();

	}

}