<?php

/**
 * Returns an error message
 *
 * @param  string  $message
 * @return void
 */
function error($message = '')
{
	include SYS_PATH . 'cobalt'.DS.'errors'.DS.'general.php';

	exit;
}

/**
 * Returns the total load time
 *
 * @param  int    $decimals
 * @return double
 */
function load_time($decimals = 5)
{
	return round(microtime(TRUE) - COBALT_START, $decimals);
}

/**
 * Returns the base URL to the website
 *
 * @return string
 */
function base_url()
{
	return Request::base_url();
}

/**
 * Returns the URL to a page
 *
 * @return string
 */
function site_url($uri = '')
{
	$page = '';

	if(Config::get('index_page') != '')
	{
		$page = Config::get('index_page') . '/';
	}

	return base_url() . $page . $uri;
}

/**
 * Returns the current URL
 *
 * @return string
 */
function current_url()
{
	return site_url(Cobalt_Base::get('request')->uri());
}

/**
 * Redirects to a page and ends execution.
 *
 * @return void
 */
function redirect($uri = '')
{
	if( ! headers_sent())
	{
		header('Location: ' . site_url($uri));	
	}

	exit;
}

/**
 * Returns the current user's IP address
 *
 * @return string
 */
function ip_address()
{
	static $ip_address;

	if(is_null($ip_address))
	{
		if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
		{
			$ip_address = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		}

		elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		elseif(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}

		elseif(isset($_SERVER['REMOTE_ADDR']))
		{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
	}

	return $ip_address;
}