<?php

return array(

	'index_page' => 'index.php',

	'autoload' => array(
		'classes' => array(),
		'modules' => array(),
	),

	'database' => array(
		'default' => array(
			'hostname' => '127.0.0.1',
			'username' => 'root',
			'password' => '',
			'database' => 'blog',
			'pconnect' => FALSE,
		),
	),

	'routes' => array(
		'default' => 'blog',
	),
);