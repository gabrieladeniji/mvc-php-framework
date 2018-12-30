<?php

/*
* Home Controller
*
* Php version 5.6
*/
namespace App\Controllers;

use Core\Controller;
use Core\View;

class Home extends Controller {

    /*
    * Call Before
    *
    * @return void
    */
    protected function before() {
    }


    /*
    * Show index page
    *
    * @return void
    */
    public function index($params, $query) {
        var_dump($query);
        View::renderTemplate('Home.index');
    }


    /*
    * Call After
    *
    * @return void
    */
    protected function after() {
    }

}




?>
