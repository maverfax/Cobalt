<?php

class Module {

	/**
	 * Verifies that a module exists
	 *
	 * @param  string  $module
	 * @return boolean
	 */
	public static function exists($module)
	{
		return (is_dir(SYS_PATH.'modules'.DS.$module.DS));
	}

	/**
	 * Loads a module, and saves its instance to the base if it exists
	 *
	 * @param  string  $module
	 * @return void
	 */
	public static function load($module)
	{
		if(static::exists($module))
		{
			Cobalt_Base::set($module, new Module_Instance($module));
		}
	}
}

class Module_Instance {

	/**
	 * Stores the name of the module
	 *
	 * @var string
	 */
	private $module = NULL;

	/**
	 * Stores the path to the module
	 *
	 * @var string
	 */
	private $path   = NULL;

	/**
	 * Saves the name of the module and determines the path to the module
	 *
	 * @param  string  $module
	 * @return void
	 */
	public function __construct($module)
	{
		$this->module = $module;
		$this->path   = SYS_PATH.'modules'.DS.$module.DS;
	}

	/**
	 * Loads a module's class
	 *
	 * @param  string  $class
	 * @return void
	 */
	public function load($class)
	{
		$this->$class = \Cobalt_Base::get($this->module.'\\'.$class);

		return $this->$class;
	}

	/**
	 * Loads a module's view with the given data
	 *
	 * @param  string  $_file
	 * @param  array   $data
	 * @return void
	 */
	public function view($_file, $data = array())
	{
		extract(Cobalt_Base::data($data));

		include $this->path.'views'.DS.$_file.'.php';
	}

	/**
	 * Autoloads a module's class
	 *
	 * @param  string  class
	 * @return Object
	 */
	public function __get($class)
	{
		return $this->load($class);
	}
}