<?php

include_once 'db-connect.php';

class OfflineData {

    //tätä pitää muokata myöhemmmin niin, että se lataa vain käyttäjän ostamien artikkelien sun muiden tiedot
    private $db;
    private $db_table = "kaikkiartikkelit";
    private $db_table2 = "kaikkilehdet";
    private $db_table3 = "muutlehdet";
    private $db_table4 = "ruokalehdet";
    private $db_table5 = "tiedelehdet";
    private $db_table6 = "urheilulehdet";
    private $db_table7 = "toimittajat";

    public function __construct() {
        $this->db = new DbConnect();
    }

    public function loadArticles() {
        $json['articles'] = array();
        $query = "select * from " . $this->db_table;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function loadMagazines() {
        $json['magazines'] = array();
        $query = "select * from " . $this->db_table2;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function otherMagazines() {
        $json['othermagazines'] = array();
        $query = "select * from " . $this->db_table3;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function foodMagazines() {
        $json['foodmagazines'] = array();
        $query = "select * from " . $this->db_table4;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function scienceMagazines() {
        $json['sciencemagazines'] = array();
        $query = "select * from " . $this->db_table5;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function sportMagazines() {
        $json['sportmagazines'] = array();
        $query = "select * from " . $this->db_table6;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function loadAuthors() {
        $json['authors'] = array();
        $query = "select * from " . $this->db_table7;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

}
