<?php
class DB {
    function dbc() {
        $host = 'localhost';
        $dbname = 'file_db';
        $user = 'root';
        $pass = 'root';
        $dns = "mysql:host=$host; dbname={$dbname};charset=utf8";

        try {
            $pdo = new PDO($dns, $user, $pass);
            return $pdo;
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }
}