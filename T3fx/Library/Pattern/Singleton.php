<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 11:31
 */

namespace T3fx\Library\Pattern;


abstract class Singleton {

	/**
	 * instance
	 *
	 * Instances of singleton classes
	 *
	 * @var Singleton
	 */
	protected static $instances = [];

	/**
	 * get instance
	 *
	 * Create the instance of the class if not exist and return it
	 *
	 * @return   Singleton
	 */
	final public static function getInstance() {

		$calledClass = get_called_class();

		if (!isset($instances[$calledClass])) {
			self::$instances[$calledClass] = new $calledClass();
			if(method_exists(self::$instances[$calledClass], 'init')) {
				self::$instances[$calledClass]->init();
			}
		}

		return self::$instances[$calledClass];
	}

	/**
	 * clone
	 *
	 * prohibit external copy of the instance
	 */
	final private function __clone() {}

	/**
	 * constructor
	 *
	 * prohibit external use of constructor
	 */
	final private function __construct() {}

}

