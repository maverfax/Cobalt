<?php

class Form_Validation {

	private $rules  = array();
	private $input  = array();
	private $data   = array();
	private $errors = array();

	/**
	 * Sets the default form data and rules
	 *
	 * @param  array  $rules
	 * @param  string $data
	 * @return void
	 */
	public function __construct($rules = array(), $data = FALSE)
	{
		$this->set_rules($rules);
		$this->set_data($_POST, TRUE);

		if(is_array($data))
		{
			$this->set_data($data);
		}
	}

	/**
	 * Sets additional rules to validate
	 *
	 * @param  array  $rules
	 * @param  string $data
	 * @return void
	 */
	public function set_rules($rules)
	{
		$this->rules = array_merge($this->rules, $rules);
	}

	/**
	 * Sets additional data to be validated
	 *
	 * @param  array  $rules
	 * @param  string $data
	 * @return void
	 */
	public function set_data($data, $strip = FALSE)
	{
		if($strip && get_magic_quotes_gpc())
		{
			foreach($data as $key => $value)
			{
				$data[$key] = stripslashes($value);
			}
		}

		$this->data  = array_merge($this->data, $data);
		$this->input = $this->data;
	}

	/**
	 * Fetches an input
	 *
	 * Note: this does not reset if the form is correctly validated
	 *
	 * @param  string $field
	 * @return string
	 */
	public function input($field = NULL)
	{
		if(is_null($field))
		{
			return $this->input;
		}

		else
		{
			if(isset($this->input[$field]))
			{
				return $this->input[$field];
			}
		}

		return NULL;
	}

	/**
	 * Fetches a piece of data from the current form.
	 *
	 * Note: this resets if the form is correctly validated
	 *
	 * @param  string  $field
	 * @return string
	 */
	public function value($field = NULL)
	{
		if(is_null($field))
		{
			return $this->data;
		}

		else
		{
			if(isset($this->data[$field]))
			{
				return $this->data[$field];
			}
		}

		return NULL;
	}

	/**
	 * Validates the form
	 *
	 * @return boolean
	 */
	public function run()
	{
		foreach($this->rules as $field => $rules)
		{
			if( ! isset($this->data[$field]))
			{
				if(in_array('required', $rules))
				{
					return FALSE;
				}

				continue;
			}

			foreach($rules as $rule)
			{
				$param = NULL;

				if(strpos($rule, ':'))
				{
					list($rule, $param) = explode(':', $rule);
				}

				if($this->$rule($field, $this->data[$field], $param) === FALSE)
				{
					return FALSE;
				}
			}
		}

		$this->clear();

		return TRUE;
	}

	/**
	 * Returns all of the errors
	 *
	 * @param  string  $append
	 * @param  string  $prepend
	 * @return string
	 */
	public function error($append = '', $prepend = '')
	{
		$output = '';

		foreach($this->errors as $error)
		{
			$output .= $append . $error . $prepend;			
		}

		return $output;
	}

	/**
	 * Clears the current form data
	 *
	 * @return void
	 */
	public function clear()
	{
		$this->rules  = array();
		$this->data   = array();
		$this->errors = array();
	}

	// --------------------------------------------------------------------

	/**
	 * Field Name
	 *
	 * @param  string  $field
	 * @return string
	 */
	 private function field_name($field)
	 {
		if(strpos($field, '_'))
		{
			$field = str_replace('_', ' ', $field);
		}

		return ucwords($field);
	 }

	/**
	 * Checks if a field has been inputted
	 *
	 * @param  string  $field
	 * @param  string  $value
	 * @param  string  $next_field
	 * @return bool
	 */
	private function required($field, $value)
	{
		if(trim($value) == '')
		{
			$this->errors[] = 'The ' . $this->field_name($field) . ' field is required';

			return FALSE;
		}
	}

	/**
	 * Checks if a field has been inputted
	 *
	 * @param  string  $field
	 * @param  string  $value
	 * @param  string  $next_field
	 * @return bool
	 */
	private function valid_email($field, $value)
	{
		 if( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value))
		 {
			$this->errors[] = 'The ' . $this->field_name($field) . ' field must contain a valid email address';

			return FALSE;
		 }
	}

	/**
	 * Checks if a field matches another field
	 *
	 * @param  string  $field
	 * @param  string  $value
	 * @param  string  $next_field
	 * @return bool
	 */
	private function matches($field, $value, $next_field)
	{
		if($value != $this->input($next_field))
		{
			$this->errors[] = 'The ' . $this->field_name($field) . ' field does not match the Confirm ' . $this->field_name($field) . ' field';

			return FALSE;
		}
	}
}