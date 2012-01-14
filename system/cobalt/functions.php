<?php

function error($message = '')
{
	include SYS_PATH . 'cobalt'.DS.'errors'.DS.'general.php';

	exit;
}

function load_time($decimals = 5)
{
	return round(microtime(TRUE) - COBALT_START, $decimals);
}

function base_url()
{
	return Request::base_url();
}

function site_url($uri = '')
{
	return base_url() . 'index.php/' . $uri;
}

function redirect($uri = '')
{
	header('Location: ' . site_url($uri));
}