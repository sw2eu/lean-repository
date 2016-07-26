<?php

namespace Sw2\LeanRepository;

use LeanMapper\Entity;
use LeanMapper\Fluent;
use Nette;

/**
 * Class AbstractQuery
 * @package Sw2\LeanRepository
 */
abstract class AbstractQuery
{
	/** @var Fluent */
	protected $fluent;

	/**
	 * Support for magic methods.
	 * @param string $name
	 * @param array $arguments
	 * @return AbstractQuery
	 */
	public function __call($name, $arguments)
	{
		if ($this->fluent === NULL) {
			throw new Nette\NotImplementedException("Query should have set 'fluent' property!");
		}
		$reflection = self::getReflection();
		$commandMethod = 'command' . ucfirst($name);
		if ($reflection->hasMethod($commandMethod)) {
			$this->$commandMethod(...$arguments);
		} else {
			$this->fluent->$name(...$arguments);
		}
		return $this;
	}

	/**
	 * @return Fluent
	 */
	public function getFluent()
	{
		return $this->fluent;
	}

	/**
	 * Access to reflection.
	 * @return Nette\Reflection\ClassType|\ReflectionClass
	 */
	public static function getReflection()
	{
		$class = class_exists(Nette\Reflection\ClassType::class) ? Nette\Reflection\ClassType::class : 'ReflectionClass';
		return new $class(get_called_class());
	}

	/**
	 * @return Entity|null
	 */
	abstract public function get();

	/**
	 * @return mixed
	 */
	abstract public function find();

}
