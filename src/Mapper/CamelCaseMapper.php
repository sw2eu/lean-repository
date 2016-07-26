<?php

namespace Sw2\LeanRepository\Mapper;

use LeanMapper\DefaultMapper;
use LeanMapper\Exception\InvalidStateException;
use LeanMapper\Row;

/**
 * Class CamelCaseMapper
 * @package Sw2\LeanRepository\Mapper
 */
class CamelCaseMapper extends DefaultMapper
{
	/** @var string */
	protected $defaultEntityNamespace = 'App\Model';

	/**
	 * @inheritdoc
	 */
	public function getTable($entityClass)
	{
		return self::toUnderScore($this->trimNamespace($entityClass));
	}

	/**
	 * @inheritdoc
	 */
	public function getEntityClass($table, Row $row = NULL)
	{
		$name = ucfirst(self::toCamelCase($table));
		return $this->defaultEntityNamespace . "\\$name\\$name";
	}

	/**
	 * @inheritdoc
	 */
	public function getColumn($entityClass, $field)
	{
		return self::toUnderScore($field);
	}

	/**
	 * @inheritdoc
	 */
	public function getEntityField($table, $column)
	{
		return self::toCamelCase($column);
	}

	/**
	 * @inheritdoc
	 */
	public function getTableByRepositoryClass($repositoryClass)
	{
		$matches = [];
		if (preg_match('#([a-z0-9]+)repository$#i', $repositoryClass, $matches)) {
			return self::toUnderScore($matches[1]);
		}
		throw new InvalidStateException('Cannot determine table name.');
	}


	/**
	 * @param string $str
	 * @return string
	 */
	public static function toUnderScore($str)
	{
		return lcfirst(preg_replace_callback('#(?<=.)([A-Z])#', function ($m) {
			return '_' . strtolower($m[1]);
		}, $str));
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public static function toCamelCase($str)
	{
		return preg_replace_callback('#_(.)#', function ($m) {
			return strtoupper($m[1]);
		}, $str);
	}
}
