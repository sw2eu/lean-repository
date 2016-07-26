<?php

namespace Sw2\LeanRepository;

use LeanMapper\Entity;
use LeanMapper\Fluent;

/**
 * Class Repository
 * @package Sw2\LeanRepository
 */
abstract class Repository extends \LeanMapper\Repository
{

	/**
	 * Start new query
	 * @return RepositoryQuery
	 */
	public function query()
	{
		return new RepositoryQuery($this->createFluent(), $this);
	}

	/**
	 * @param mixed $id
	 * @return Entity|null
	 */
	public function getByID($id)
	{
		$table = $this->getTable();
		$primaryKey = $this->mapper->getPrimaryKey($table);
		return $this->getBy([["[$table.$primaryKey] = ?", $id]]);
	}

	/**
	 * @param array $criteria
	 * @return Entity|null
	 */
	public function getBy(array $criteria)
	{
		$row = self::applyCriteria($this->createFluent(), $criteria)->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	/**
	 * @param RepositoryQuery $query
	 * @return Entity|null
	 */
	public function getByQuery(RepositoryQuery $query)
	{
		$row = $query->getFluent()->fetch();
		return $row ? $this->createEntity($row) : NULL;
	}

	/**
	 * @param array $criteria
	 * @return mixed
	 */
	public function findBy(array $criteria)
	{
		$rows = self::applyCriteria($this->createFluent(), $criteria)->fetchAll();
		return $this->createEntities($rows);
	}

	/**
	 * @param RepositoryQuery $query
	 * @return mixed
	 */
	public function findByQuery(RepositoryQuery $query)
	{
		return $this->createEntities($query->getFluent()->fetchAll());
	}

	/**
	 * @param Fluent $fluent
	 * @param array $criteria
	 * @return Fluent
	 */
	private static function applyCriteria(Fluent $fluent, array $criteria)
	{
		foreach ($criteria as $key => $val) {
			if (is_int($key)) {
				$fluent->where(...$val);
			} else {
				$fluent->where($key, $val);
			}
		}
		return $fluent;
	}

}
