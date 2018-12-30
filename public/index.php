<?php

/*
* Front controller
*/

// Require the controller class
// require '../App/Controllers/Home.php';

// Require the Router class
// require '../Core/Router.php';

/*
 * Require Composer
 */
require '../vendor/autoload.php';

/*
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

use Core\Router;

Router::add('',                                  'Home@index');
Router::add('/post/<id>',                             'Post@index');

Router::dispatch( $_SERVER['QUERY_STRING']);

?>
