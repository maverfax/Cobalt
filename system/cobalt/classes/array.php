<?php

/**
 * This class was shamelessly stolen directly from the Laravel
 * framework.
 */
class Arr {

	/**
	 * Get an item from an array.
	 *
	 * "Dot" notation may be used to dig deep into the array.
	 *
	 * <code>
	 *		// Get the $array['user']['name'] value from the array
	 *		$name = Arr::get($array, 'user.name');
	 *
	 *		// Return a default from if the specified item doesn't exist
	 *		$name = Arr::get($array, 'user.name', 'Taylor');
	 * </code>
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = NULL)
	{
		foreach (explode('.', $key) as $segment)
		{
			if ( ! is_array($array) or ! array_key_exists($segment, $array))
			{
				return $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Set an array item to a given value.
	 *
	 * The same "dot" syntax used by the "get" method may be used here.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * <code>
	 *		// Set the $array['user']['name'] value on the array
	 *		Arr::set($array, 'user.name', 'Taylor');
	 * </code>
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function set(&$array, $key, $value)
	{
		if (is_null($key)) return $array = $value;

		$keys = explode('.', $key);

		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if ( ! isset($array[$key]) or ! is_array($array[$key]))
			{
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}
}