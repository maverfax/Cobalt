<?php

class Request {

	/**
	* The segments of the URI
	*
	* @var array
	*/
	private $segments = array();

	/**
	 * The URI of the request
	 *
	 * @var string
	 */
	 private $uri = NULL;

	/**
	 * Registers the URI and determines.
	 *
	 * @param  mixed  $uri
	 */
	public function __construct($uri = FALSE)
	{
		$uri = ($uri == FALSE) ? $this->uri() : $uri;

		$this->segments = explode('/', $uri);
	}

	/**
	 * Get the current URL
	 *
	 * @return string
	 */
	public function base_url()
	{
		if (isset($_SERVER['HTTP_HOST']))
		{
			$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
			$base_url .= '://'. $_SERVER['HTTP_HOST'];
			$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		}

		else
		{
			$base_url = 'http://localhost/';
		}

		return $base_url;
	}

	/**
	 * Get the current request URI
	 *
	 * @return string
	 */
	public function uri()
	{
		if(is_null($this->uri))
		{
			$this->uri = $this->_detect_uri();
			$this->uri = ($this->uri == '/') ? '' : $this->uri;
		}

		return $this->uri;
	}

	/**
	 * Detect URI
	 *
	 * @return string
	 */
	protected function _detect_uri()
	{
		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		$uri = $_SERVER['REQUEST_URI'];

		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
		{
			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		}

		elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
		{
			$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (strncmp($uri, '?/', 2) === 0)
		{
			$uri = substr($uri, 2);
		}

		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];

		if (isset($parts[1]))
		{
			$_SERVER['QUERY_STRING'] = $parts[1];
			parse_str($_SERVER['QUERY_STRING'], $_GET);
		}
		else
		{
			$_SERVER['QUERY_STRING'] = '';
			$_GET = array();
		}

		if ($uri == '/' || empty($uri))
		{
			return '/';
		}

		$uri = parse_url($uri, PHP_URL_PATH);

		// Do some final cleaning of the URI and return it
		return str_replace(array('//', '../'), '/', trim($uri, '/'));
	}

	/**
	 * Get the specified segment
	 *
	 * @param  int    $segment
	 * @param  mixed  $return
	 * @return string
	 */
	 public function segment($segment, $return = FALSE)
	 {
	 	if(isset($this->segments[$segment-1]))
		{
			return $this->segments[$segment-1];
		}

		return $return;
	 }
}