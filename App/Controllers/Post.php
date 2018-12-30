<?php

/*
* Home Controller
*
* Php version 5.6
*/
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Posts;

class Post extends Controller {

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
        var_dump($params);
        $posts = Posts::all();
        View::renderTemplate('Post.index', ['posts' => $posts]);
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
