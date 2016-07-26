<?php

namespace Sw2\LeanRepository;

use LeanMapper\Entity;
use LeanMapper\Fluent;


/**
 * Class RepositoryQuery
 * @package Sw2\LeanRepository\Query
 *
 * @method RepositoryQuery where(...$args)
 * @method RepositoryQuery orderBy($field)
 * @method RepositoryQuery limit(int $limit)
 * @method RepositoryQuery offset(int $offset)
 */
class RepositoryQuery extends AbstractQuery
{
	/** @var Repository */
	private $repository;

	public function __construct(Fluent $fluent, Repository $repository)
	{
		$this->fluent = $fluent;
		$this->repository = $repository;
	}

	/**
	 * @return Entity|null
	 */
	public function get()
	{
		return $this->repository->getByQuery($this);
	}

	/**
	 * @return mixed
	 */
	public function find()
	{
		return $this->repository->findByQuery($this);
	}
}
