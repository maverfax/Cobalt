<?php

class Cobalt_Base {

	/**
	 * Stores the instances of classes.
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * Stores the view data
	 *
	 * @var array
	 */
	 private static $data = array();

	/**
	 * Save an instance to the base
	 *
	 * @param  string  $class
	 * @param  string  $instance
	 * @return void
	 */
	public static function set($class, $instance)
	{
		static::$instances[$class] = $instance;
	}

	/**
	 * Get an instance
	 *
	 * @param  string  $class
	 * @return Object
	 */
	public static function get($class)
	{
		if(isset(static::$instances[$class]))
		{
			return static::$instances[$class];
		}

		else
		{
			if(Module::exists($class))
			{
				Module::load($class);
			}

			else
			{
				Autoloader::load($class);

				static::set($class, new $class);
			}

			return static::$instances[$class];
		}

		return FALSE;
	}

	/**
	 * Sets and returns the new view data
	 *
	 * @param  array  $data
	 * @return array
	 */
	 public static function data($data = NULL)
	 {
	 	if(is_array($data))
		{
	 		static::$data = array_merge(static::$data, $data);
		}

		return static::$data;
	 }
}

class Cobalt {

	/**
	 * Returns an instance from the Cobalt Base
	 *
	 * @param  string  $key
	 * @return Object
	 */
	public function __get($key)
	{
		return Cobalt_Base::get($key);
	}
}