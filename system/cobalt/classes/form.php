<?php

class Form {

	public static function open($action = '', $attributes = 'post')
	{
		if($action = '')
		{
			$action = Base::get('request')->uri();
		}

		return '<form method="'.$attributes.'" action="'.site_url($action).'">'.PHP_EOL;
	}

	public static function close()
	{
		return '</form>'.PHP_EOL;
	}

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

	public static function hidden($data, $value = NULL, $extra = NULL)
	{
		if( ! is_null($extra))
		{
			$extra = array();
		}

		$extra['type'] = 'hidden';

		return static::input($data, $value, $extra);
	}

	public static function password($data, $value = NULL, $extra = NULL)
	{
		if( ! is_null($extra))
		{
			$extra = array();
		}

		$extra['type'] = 'password';

		return static::input($data, $value, $extra);
	}

	public static function submit($value = NULL, $extra = NULL)
	{
		if( ! is_null($extra))
		{
			$extra = array();
		}

		$extra['type'] = 'submit';

		return static::input(strtolower($value), $value, $extra);
	}
}