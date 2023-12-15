<?php 

namespace App\Common;
use PDO;

class Singleton {
    private static $instance = null;

    private function __construct() {
        $this->pdo = new PDO('mysql:host=db;dbname=tp', 'root', 'root');
    }

    public static function getInstance() : PDO {
        if (self::$instance === null) {
            self::$instance = new Singleton();
        }
        return self::$instance->pdo;
    }
}