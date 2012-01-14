<?php

define('DS', DIRECTORY_SEPARATOR);
define('SYS_PATH', dirname(dirname(__FILE__)).DS);
define('COBALT_START', microtime(TRUE));

include SYS_PATH . 'cobalt'.DS.'functions.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'autoloader.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'cobalt.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'request.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'dispatcher.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'config.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'array.php';
include SYS_PATH . 'cobalt'.DS.'classes'.DS.'modules.php';

spl_autoload_register('Autoloader::load');