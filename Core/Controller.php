<?php

/*
* Controller class
*
* Php version 5.6
*/

namespace Core;

abstract class Controller {

    /*
    * Call the method whenever method is not accessible or not specified
    *
    * @param string $method The method name
    * @param string $args The method argument
    *
    * @return void
    */
    public function __call($method, $args) {

        $method = preg_replace('/@Action/', '', $method);

        if( method_exists($this, $method) ) {
            if( $this->before() !== false ) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method does not exits in Controller " . get_class($this), 500);
        }
    }

    /*
    * Before filter - call before Action method
    *
    * @return boolean
    */
    protected function before() {

    }

    /*
    * After filter - call after Action method
    *
    * @return boolean
    */
    protected function after() {

    }

//    /*
//    * Parameters from the matched route
//    * @var array
//    */
//    protected $route_params = [];
//
//    /*
//    * Class contructor
//    *
//    * @param $route_params The route paramaters
//    *
//    * @return null
//    */
//    public function __construct($route_params) {
//        $this->route_params = $route_params;
//    }

}



?>
