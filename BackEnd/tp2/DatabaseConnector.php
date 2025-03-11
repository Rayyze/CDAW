<?php

require_once 'config.php';

class DatabaseConnector {

    protected static $pdo = NULL;

    public static function current(){
       if(is_null(static::$pdo))
          static::createPDO();

       return static::$pdo;
    }

    protected static function createPDO() {
        // $db = new PDO("sqlite::memory");

        $connectionString = "mysql:host=". _MYSQL_HOST;

        if(defined('_MYSQL_PORT'))
            $connectionString .= ";port=". _MYSQL_PORT;

        $connectionString .= ";dbname=" . _MYSQL_DBNAME;

        static::$pdo = new PDO($connectionString,_MYSQL_USER,_MYSQL_PASSWORD);
        static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}