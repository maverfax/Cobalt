<?php

class Autoloader {

	/**
	 * Autoload a class
	 *
	 * @param  string  $class
	 * @return void
	 */
	public static function load($class)
	{
		list($path, $file) = static::parse($class);

		if(file_exists(SYS_PATH . $path . $file))
		{
			include SYS_PATH . $path . $file;
		}

		else
		{
			error("Class '$class' could not be found");
		}
	}

	/**
	 * Get the path, file name, and name of a class off of its namespace 
	 *
	 * @param  string  $class
	 * @return array
	 */
	protected static function parse($class)
	{
		$class     = strtolower($class);
		$namespace = strrpos($class, '\\');

		if($namespace === FALSE)
		{
			// Non-namespaced classes are part of the core
			$path = 'cobalt'.DS.'classes'.DS;
		}

		else
		{
			// Namespaced classes require a little more work as the path
			// varies on the namespace
			$module = substr($class, 0, $namespace);
			$class  = substr($class, $namespace + 1);

			// Is the class further namespaced?
			if(strpos('\\', $class))
			{
				// TODO
			}

			else
			{
				$path = 'modules'.DS.$module.DS.'classes'.DS;
			}
		}

		return array($path, $class.'.php');
	}
}