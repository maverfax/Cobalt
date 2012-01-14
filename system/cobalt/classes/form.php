<?php

class Form {

	/**
	 * Opens a form tag
	 *
	 * @param  string  $action
	 * @param  string  $attributes
	 * @return string
	 */
	public static function open($action = NULL, $attributes = 'post')
	{
		if(is_null($action))
		{
			$action = Cobalt_Base::get('request')->uri();
		}

		return '<form method="'.$attributes.'" action="'.site_url($action).'">'.PHP_EOL;
	}

	/**
	 * Closes a form tag
	 *
	 * @return string
	 */
	public static function close()
	{
		return '</form>'.PHP_EOL;
	}

	/**
	 * Creates an input tag
	 *
	 * @param  mixed   $data
	 * @param  string  $value
	 * @param  array   $extra
	 * @return string
	 */
	public static function input($data, $value = NULL, $extra = NULL)
	{
		// Build the input's data
		if( ! is_array($data))
		{
			$data = array(
				'type' => 'text',
				'name' => $data,
				'value' => $value,
			);
		}

		else
		{
			if( ! isset($data['type']))
			{
				$data['type'] = 'text';
			}
		}

		if(is_array($extra))
		{
			$data = array_merge($data, $extra);
		}

		// Build the output
		$output = '<input';

		foreach($data as $attribute => $value)
		{
			$output .= ' ' . $attribute . '="'.$value.'"';
		}

		$output .= '>'.PHP_EOL;

		return $output;
	}

	/**
	 * Creates a textarea tag
	 *
	 * @param  mixed   $data
	 * @param  string  $value
	 * @param  array   $extra
	 * @return string
	 */
	public static function textarea($data, $value = NULL, $extra = array())
	{
		$output = '<textarea';

		if( ! is_array($data))
		{
			$data = array('name' => $data);
		}

		$data = array_merge($data, $extra);

		if(isset($data['value']))
		{
			$value = $data['value'];

			unset($data['value']);
		}

		foreach($data as $attribute => $val)
		{
			$output .= ' ' . $attribute . '="'.$val.'"';
		}

		$output .= '>'.$value.'</textarea>';

		return $output;
	}

	/**
	 * Creates a hidden tag
	 *
	 * @param  mixed   $data
	 * @param  string  $value
	 * @param  array   $extra
	 * @return string
	 */
	public static function hidden($data, $value = NULL, $extra = NULL)
	{
		if( ! is_null($extra))
		{
			$extra = array();
		}

		$extra['type'] = 'hidden';

		return static::input($data, $value, $extra);
	}

	/**
	 * Creates a password tag
	 *
	 * @param  mixed   $data
	 * @param  string  $value
	 * @param  array   $extra
	 * @return string
	 */
	public static function password($data, $value = NULL, $extra = NULL)
	{
		if(is_null($extra))
		{
			$extra = array();
		}

		$extra['type'] = 'password';

		return static::input($data, $value, $extra);
	}

	/**
	 * Creates a submit tag
	 *
	 * @param  string  $value
	 * @param  array   $extra
	 * @return string
	 */
	public static function submit($value = NULL, $extra = NULL)
	{
		if(is_null($extra))
		{
			$extra = array();
		}

		$extra['type'] = 'submit';

		return static::input(strtolower($value), $value, $extra);
	}
}