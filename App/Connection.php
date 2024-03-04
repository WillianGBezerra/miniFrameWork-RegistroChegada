<?php

    namespace App;

    class Connection {

        public static function getDb() {
            try {

                $conn = new \PDO(
                    "mysql:host=localhost;dbname=dbregistrochegada;charset=utf8",
                    "client",
                    "YbQYeHtHHVYr@QFp"
                );

                return $conn;
            } catch(\PDOException $e) {
                //...tratar erro...//
            }
        }
    }
?>