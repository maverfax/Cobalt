<?php
/**
 * Cobalt - A simple RAD PHP framework
 *
 * @package  Cobalt
 * @version  1.0.0 Beta
 */

/*
 |---------------------------------------------------------------
 | Load the Bootstrapper
 |---------------------------------------------------------------
 |
 | Load the base classes and register the autoloader.
 |
 */
 include 'system/cobalt/bootstrap.php';

/*
 |---------------------------------------------------------------
 | Run the  Request
 |---------------------------------------------------------------
 |
 | Send a response for the Request.
 |
 */
 Dispatcher::run(new Request);