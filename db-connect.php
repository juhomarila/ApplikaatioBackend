<?php

class DbConnect {

    private $connect;

    public function __construct() {


        $server = "localhost";
        $usr = "root";
        $psw = "";
        $db = "appi";

        try {
            $this->connect = new PDO("mysql:host=$server;dbname=$db", $usr, $psw);
            
        } catch (Exception $ex) {
            
            echo "Unbale to connect to MySQL Database: " . + $ex;
        } 
    }

    public function getDb() {
        return $this->connect;
    }

}
