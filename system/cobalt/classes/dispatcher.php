<?php

class Dispatcher {

	/**
	 * Run a controller
	 *
	 * @param  mixed  $route
	 * @return void
	 */
	public static function run($request)
	{
		// If we're given a string, turn it into a fake request
		if( ! $request instanceof Request)
		{
			$request = new Request($request);
		}

		// Determine the action to run
		list($module, $action) = static::action($request);

		$path = SYS_PATH.'modules'.DS;

		if(file_exists($path.$module.DS.'routes.php'))
		{
			// Boot up the module
			Module::load($module);

			// Run the controller's action
			include $path.$module.DS.'routes.php';

			$class = $module.'\Routes';
			$class = new $class;

			if(method_exists($class, $action))
			{
				// Save the new instances to the base
				Cobalt_Base::set('controller', $class);
				Cobalt_Base::set('request', $request);

				// Now run the action
				return $class->$action();
			}
		}

		error('FO-OH-FO BRO');
	}

	/**
	 * Get the module and method from a Request
	 *
	 * @param  Request  $request
	 * @return array
	 */
	private static function action($request)
	{
		$module = Config::get('routes.default');
		$action = 'index';

		if($request->uri() != '')
		{
			$module = $request->segment(1);

			if($request->segment(2))
			{
				$action = $request->segment(2);
			}
		}

		return array($module, $action);
	}
}