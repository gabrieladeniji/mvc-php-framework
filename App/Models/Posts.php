<?php

/*
 *  Post Model
 *
 * Php version 5.6
 */

namespace App\Models;

use Core\Model;

class Posts extends Model {

    public static function all() {
        $db = static::db();
        try {
            $stmt = $db->query('SELECT * FROM posts ORDER BY created_at');
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            throw new \Exception( $e->getMessage() );
        }
    }
}

?>