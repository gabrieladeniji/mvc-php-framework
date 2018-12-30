<?php

/*
* View class
*
* php version
*/

namespace Core;

Use eftec\bladeone;

class View {

    /*
    * Rebder the view file
    *
    * @param String $view The view file
    *
    * @return void
    */
    public static function render($view, $args = []) {

        // Turns array key into variable name and array value into variable value
        extract($args, EXTR_SKIP);

        $file = '../App/Views/' . $view;
        if( is_readable($file) ) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }

    }

    /*
     * Render BladeOne template
     *
     * @param string $template
     * @param array $args
     *
     * @return void
     */
    public static function renderTemplate($template, $args = []) {
        $views = '../App/Views/'; // it uses the folder /views to read the templates
        $cache = '../App/Cache/'; // it uses the folder /cache to compile the result.
        $blade = new bladeone\BladeOne($views, $cache, bladeone\BladeOne::MODE_AUTO);
        echo $blade->run($template, $args); // /views/hello.blade.php must exist
    }

    /*
     * Redirect to specified url
     *
     * @param string $url The route url
     *
     * @return void
     */
    public static function redirect($url) {
        header("Location: $url");
    }

}



?>
