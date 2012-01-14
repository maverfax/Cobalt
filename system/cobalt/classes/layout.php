<?php

class Layout extends Cobalt {

	/**
	 * View Data
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Sets view data
	 *
	 * @param  array  $data
	 * @return Layout
	 */
	public function data($data)
	{
		if( ! is_array($data))
		{
			return;
		}

		$this->data = array_merge($this->data, $data);

		return $this;
	}

	/**
	 * Displays a page with its layout
	 *
	 * @param  string  $module
	 * @param  string  $action
	 * @param  string  $layout
	 * @return Layout
	 */
	public function build($module, $action, $layout)
	{
		$path  = SYS_PATH.'modules'.DS;
		$path .= $module.DS.'views'.DS;

		if(file_exists($path . $action . '.php'))
		{
			extract($this->data);

			ob_start();

			include $path . $action.'.php';

			$this->load($layout, ob_get_clean());
		}

		return $this;
	}

	/**
	 * Loads a layout
	 *
	 * @param  string  $layout
	 * @return Layout
	 */
	public function load($layout, $content = '')
	{
		if($layout == FALSE)
		{
			echo $content;
		}

		else
		{
			include SYS_PATH.'template'.DS.$layout.'.php';
		}

		return $this;
	}

	/**
	 * Loads a layout partial
	 *
	 * @param  string  $view
	 * @return Layout
	 */
	public function partial($_view)
	{
		extract($this->data);

		include SYS_PATH.'template'.DS.'partials'.DS.$_view.'.php';

		return $this;
	}
}