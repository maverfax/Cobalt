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
 * Returns the base url to the website
 *
 * @return string
 */
function base_url()
{
	return Request::base_url();
}

/**
 * Returns the url to a page
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