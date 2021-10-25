<?php

include_once 'db-connect.php';

class Writer {

    private $db;
    private $db_table = "kaikkiartikkelit"; //tee db joka lehdelle erikseen tai sitten renameta kaikkiartikkelit
    private $db_table2 = "toimittajat";

    public function __construct() {
        $this->db = new DbConnect();
    }

    public function checkIfWriterExists($name) {
        $query = "select * from " . $this->db_table . " where author = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
        $result->execute();
        $rows = $result->rowCount();

        if ($rows > 0) {
            $this->db = null;
            return true;
        } else {
            $this->db = null;
            return false;
        }
    }

    public function createWriter($image, $name, $occupation) {

        if ($this->checkIfWriterExists($name == true)) {
            $query = "insert into " . $this->db_table2 . " (name, occupation, image) values (:name, :occupation, :image)";
            $result = $this->db->getDb()->prepare($query);
            $result->bindParam(':name', $name);
            $result->bindParam(':occupation', $occupation);
            $result->bindParam(':image', $image);

            $result->execute();

            if ($result == true) {
                $json['success'] = 1;
                $json['message'] = "Successfully created writer";
            } else {
                $json['success'] = 0;
                $json['message'] = "Vittu";
            }
            $this->db = null;
            return $json;
        } else {
            $json['success'] = 0;
            $json['message'] = "Vittu";
        }
        return $json;
    }

    public function getWriterArticleNames($name) {
        $json['articles'] = array();
        $query = "select name from " . $this->db_table . " where author = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function getWriterMagazines($name) {
        $json['magazines'] = array();
        $query = "select distinct magazine_name from " . $this->db_table . " where author = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        return $json;
    }

    public function getWriterInfo($name) {
        $query = "select occupation, image from " . $this->db_table2 . " where name = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['occupation'] = $result->occupation;
            $json['image'] = $result->image;
        }

        return $json;
    }
    
        public function getWriterArticles($name) {
        $json['articles'] = array();
        $query = "select * from " . $this->db_table . " where author = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
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
