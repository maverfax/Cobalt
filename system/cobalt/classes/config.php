<?php

class Config {

	/**
	 * Has the config file been loaded yet?
	 *
	 * @var boolean
	 */
	static private $loaded  = FALSE;

	/**
	 * Stores the loaded configs
	 *
	 * @var array
	 */
	static private $config = array();

	/**
	 * Loads the config file
	 *
	 * @param  string  $file
	 * @return void
	 */
	public static function load()
	{
		static::$loaded = TRUE;
		static::$config = include SYS_PATH.'config.php';
	}

	/**
	 * Fetches a configuration
	 *
	 * @param  string  $config
	 * @return mixed
	 */
	public static function get($config)
	{
		if( ! static::$loaded)
		{
			static::load();
		}

		return Arr::get(static::$config, $config);
	}
}