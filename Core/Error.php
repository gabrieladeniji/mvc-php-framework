<?php

namespace Core;

/*
 * Handle Error and Exception
 *
 * php version 7.0
 */

use App\Config\App as app;

class Error {

    /*
     * Convert all errors to exception by throwing ErrorException
     * @param int $level Error level
     * @param string $message The error message
     * @param string $file The filename the error was raised in
     * @param int $line Line number in the file
     * @throws \ErrorException
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line) {
        if( error_reporting() !== 0 ) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }


    public static function exceptionHandler($exception) {

        $code = ($exception->getCode() != '404' ? '500' : '404');
        http_response_code($code);

        if( preg_match('/^prod/i', app::app_mode) ) {
            $err_msg = "Uncaught exception: '". get_class($exception)."' \n";
            $err_msg .= $exception->getMessage() ." \n";
            $err_msg .= $exception->getTraceAsString(). " \n";
            $err_msg .= "Thrown in '". $exception->getFile() ."' on line '". $exception->getLine() ."' \n";
            // log error
            ini_set('error_log', dirname(__DIR__) . '/logs/' . date('d-m-Y') . '.log');
            error_log( $err_msg );
            // display error
            static::displayErrorPage($code);
        } else {
            echo "<h1> Fatal Error </h1>";
            echo "<p> Uncaught exception: '". get_class($exception) ."' </p>";
            echo "<p> Message: '". $exception->getMessage() ."' </p>";
            echo "<p> Stack trace: <pre> '". $exception->getTraceAsString() ."' </pre> </pre>";
            echo "<p> Thrown in '". $exception->getFile() ."' on line '". $exception->getLine() ."' </p>";
        }

    }


    /* Display the proper error page according to the http response code
     *
     * @param $code The http response code
     *
     * @return null
     */
    private static function displayErrorPage($code) {
        switch ($code) {
            case '404':
                echo "<h1> 404 Page not found </h1>";
                break;
            case '505':
                echo "<h1> Error 500 </h1>";
                break;
            default:
                echo "<h1> Unknown Error occurred </h1>";
        }
    }
}


?>