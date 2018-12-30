<?php

namespace Core;

use Symfony\Component\Finder\Finder;

/*
* Add Route to Router tables
*
* Php version 5.6
*/

class Router {

    /*
    * Associative array that stored the routes
    * @var Array
    */
    protected static $routes = [];


    /*
    * Associative array that hold the route parameters that match
    * @var Array
    */
    protected static $params = [];

    /*
     * Associative array that hold the route query string
     * @var Array
     */
    protected static $query = [];

    /*
    * Add route to routing tables
    *
    * @param string $route The route url
    * @param string $params The route controller and action
    *
    * @return
    */
    public static function add($route, $params) {

        // Replace first slash and last slash from route
        $route = preg_replace('/^\/|\/$/', '', $route);
        // Convert escpace the forward slasshes
        $route = preg_replace('/\//', '\\/', $route);
        // Convert custom variables eg <id> or <ref>
        $route = preg_replace('/\<([a-z-]+)\>/', '(?P<\1>[a-z0-9-_]+)', $route);
        // Add first and second delimiters and make it case insentitive
        $route = '/^' . $route . '$/i';
        // Split the params into Controller and Method
        preg_match('/^(?P<controller>[a-z]+)@(?P<method>[a-z]+)$/i', $params, $matches);

        self::$routes[$route] = [
            'controller' => $matches['controller'],
            'method'     => $matches['method']
        ];
    }


    /*
    * Check if url match any route from the routing table
    *
    * @paeram string $route The route url
    *
    * @return boolean
    */
    public static function match($url) {

        foreach(self::$routes as $routeRegex => $params) {
            if( preg_match($routeRegex, $url, $matches) ) {
                foreach($matches as $key => $match) {
                    if( is_string($key) ) {
                        $params['params'][$key] = $match;
                    }
                }
                self::$params = $params;
                return true;
            }
        }
        return false;

    }


    /*
    * Run the Particular route
    *
    * @paeram string $route The route url
    *
    * @return null
    */
    public static function dispatch($url) {

        self::$query = static::extractQueryStringFromUrl();
        $url = self::removeQueryString($url);

        if( self::match($url) ) {

            $controller  = self::$params['controller'];
            $controller  = self::convertToStudlyCaps($controller);
            $controller  = self::getNamespace($controller).$controller;

            if( class_exists($controller) ) {
                $controller_object = new $controller();

                $method = self::$params['method'].'@Action';
                $method = self::convertToCamelCase($method);

                if( is_callable([$controller_object, $method]) ) {
                    call_user_func_array([$controller_object, $method],
                        [
                            ( empty(self::$params['params']) ? self::$params['params'] = '' : self::$params['params'] ),
                            self::$query
                        ]
                    );
                } else {
                    throw new \Exception("Method $method in Controller $controller not found");
                }
            } else {
                throw new \Exception("Controller $controller does not exists");
            }
        } else {
            throw new \Exception("Route not found!", 404);
        }
    }


    /*
    * Display all routes from routing table
    *
    * @return array
    */
    public static function showRoutes() {
        return self::$routes;
    }


    /*
    * Display the route params that match
    *
    * @return string
    */
    public static function showParams() {
        return self::$params;
    }


    /*
    * Convert string with -_. to studlyCaps
    *
    * @param string $controller The controller class
    *
    * @return string
    */
    public static function convertToStudlyCaps($controller) {
        return str_replace(' ', '', ucwords(preg_replace('/-_./', ' ', $controller)));
    }


    /*
    * Convert string to Camelcase
    *
    * @param string $method The method in class controller
    *
    * @return string
    */
    public static function convertToCamelCase($method) {
        return lcfirst( self::convertToStudlyCaps($method) );
    }


    /*
    * Remove query string
    *
    * @param string $url The route url
    *
    * @return string
    */
    public static function removeQueryString($url) {
        if( $url != '' ) {
            // Replace first slash and last slash
            $url = preg_replace('/^\/|\/$/', '', $url);
            $parts = explode('&', $url, 2);
            // make sure query string is not included in the url
            if( strpos($parts[0], '=') === false ) {
                $url = $parts[0];
            } else {
                $url = '';
            }
//            if( count(self::$query) == 0 ) {
//                $url = str_replace('=', '', $_SERVER['REQUEST_URI']);
//            }
        }
//        ini_set('error_log', dirname(__DIR__) . '/logs/' . date('Y-D-M') . '.txt');
//        error_log( $url );
        return $url;
    }


    /*
     * Add query string from url to array and save to query properties
     *
     * @param null
     *
     * @return void
     */
    public static function extractQueryStringFromUrl() {
        // extract query string from url
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        // convert query string to array
        parse_str($query, $query_array_result);
        return $query_array_result;
    }


    /*
    * Get the class namespace
    *
    * @param string $controller The controller class
    *
    * @return string The controller namespace
    */
    public static function getNamespace($controller) {

        $namespace = 'App\Controllers\\';
        $dir = dirname(__DIR__) . '/App/Controllers/';
        $finder = new Finder();
        $finder->files()->in( $dir );
        $finder->files()->name('*' . $controller . '.php');

        foreach ($finder as $file) {
            if( $file->getRelativePath() !== '' ) {
                $namespace = 'App\Controllers\\' . $file->getRelativePath() . '\\';
            }
        }

        return $namespace;
    }



}


?>
