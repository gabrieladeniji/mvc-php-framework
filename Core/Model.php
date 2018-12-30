<?php

/* Core Model
 *
 * Php version 5.6
 */
namespace Core;

use PDO;
use App\Config\Database as config;

abstract class Model {

    /*
     * Get the PDO Database Connection
     */
    protected static function db() {

        static $db = null;

        if( $db === null ) {
            $host     = config::db_host;
            $dbname   = config::db_name;
            $username = config::db_username;
            $password = config::db_password;
            try{
                $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch( PDOException $e) {
                throw new \Exception( $e->getMessage() );
            }
        }
        return $db;
    }

}

?>