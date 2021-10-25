<?php

include_once 'db-connect.php';

class Magazine {

    private $db;
    private $db_table = "magazines";
    private $db_table2 = "users";
    private $db_table3 = "tiedelehdet";
    private $db_table4 = "urheilulehdet";
    private $db_table5 = "ruokalehdet";
    private $db_table6 = "muutlehdet";
    private $db_table7 = "kaikkilehdet";
    private $db_table8 = "likes";

    public function __construct() {

        $this->db = new DbConnect();
    }

    public function doesMagazineExist($magazine) {
        $query = "select * from " . $this->db_table . " where magazine_name = :magazine_name Limit 1";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine);
        $result->execute();
        $rows = $result->rowCount();

        if ($rows > 0) {

            $this->db = null;
            return true;
        } else {

            return false;
        }
    }

    public function addMagazine($magazine, $publisher, $founded, $editor, $hometown, $published, $image, $category) {

//$doesitexist = $this->doesMagazineExist($magazine);
//        if ($doesitexist) {
//
//            echo "Magazine already exists";
//        } else {

        if ($category == "tiedelehdet") {
            $query = "insert into " . $this->db_table3 . " (magazine_name, publisher, founded, editorinchief, hometown, published, image) "
                    . "values (:magazine_name, :publisher, :founded, :editorinchief, :hometown, :published, :image)";
        } elseif ($category == "urheilulehdet") {
            $query = "insert into " . $this->db_table4 . " (magazine_name, publisher, founded, editorinchief, hometown, published, image) "
                    . "values (:magazine_name, :publisher, :founded, :editorinchief, :hometown, :published, :image)";
        } elseif ($category == "ruokalehdet") {
            $query = "insert into " . $this->db_table5 . " (magazine_name, publisher, founded, editorinchief, hometown, published, image) "
                    . "values (:magazine_name, :publisher, :founded, :editorinchief, :hometown, :published, :image)";
        } elseif ($category == "muutlehdet") {
            $query = "insert into " . $this->db_table6 . " (magazine_name, publisher, founded, editorinchief, hometown, published, image) "
                    . "values (:magazine_name, :publisher, :founded, :editorinchief, :hometown, :published, :image)";
        }
        $query2 = "insert into " . $this->db_table7 . " (image, category) values (:image, :category)";
        $result = $this->db->getDb()->prepare($query);
        $result2 = $this->db->getDb()->prepare($query2);
        $result->bindParam(':magazine_name', $magazine);
        $result->bindParam(':publisher', $publisher);
        $result->bindParam(':founded', $founded);
        $result->bindParam(':editorinchief', $editor);
        $result->bindParam(':hometown', $hometown);
        $result->bindParam(':published', $published);
        $result->bindParam(':image', $image);
        $result2->bindParam(':image', $image);
        $result2->bindParam(':category', $category);
        $result->execute();

        if ($result == true) {
            echo "Magazine added successfully.";
        } else {
            echo "Something went wrong";
        }
//}
    }

    public function addSubscriber($magazine) {
        $query = "UPDATE " . $this->db_table . " SET subscribers = subscribers + 1 WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine);
        $result->execute();
    }

    public function removeSubscriber($magazine) {
        $query = "UPDATE " . $this->db_table . " SET subscribers = subscribers - 1 WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine);
        $result->execute();
    }

    public function getMagazineID($magazine) {
        $query = "SELECT magazine_id FROM " . $this->db_table . " WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $name = $result->magazine_id;
        }

        return $name;
    }

    public function getAllMagazines() {
        $query = "SELECT magazine_name FROM " . $this->db_table . "";
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $names = $result->fetchAll();

        return $names;
    }

    public function getMagazineInfo($image, $category) {
        $json = array();
        if ($category == "tiedelehdet") {
            $query = "select * from " . $this->db_table3 . " where image = :image";
        } elseif ($category == "urheilulehdet") {
            $query = "select * from " . $this->db_table4 . " where image = :image";
        } elseif ($category == "ruokalehdet") {
            $query = "select * from " . $this->db_table5 . " where image = :image";
        } elseif ($category == "muutlehdet") {
            $query = "select * from " . $this->db_table6 . " where image = :image";
        }
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':image', $image);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['magazine_name'] = $result->magazine_name;
            $json['publisher'] = $result->publisher;
            $json['founded'] = $result->founded;
            $json['editorinchief'] = $result->editorinchief;
            $json['hometown'] = $result->hometown;
            $json['published'] = $result->published;
        }
        return $json;
    }

    public function getCategory($image) {
        $query = "select * from " . $this->db_table7 . " where image = :image";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':image', $image);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $category = $result->category;
        }

        return $category;
    }

    public function getImageFromCertainCategory($category) {
        $json['images'] = array();
        if ($category == "tiedelehdet") {
            $query = "SELECT image FROM " . $this->db_table3;
            $result = $this->db->getDb()->prepare($query);
        } elseif ($category == "urheilulehdet") {
            $query = "SELECT image FROM " . $this->db_table4;
            $result = $this->db->getDb()->prepare($query);
        } elseif ($category == "ruokalehdet") {
            $query = "SELECT image FROM " . $this->db_table5;
            $result = $this->db->getDb()->prepare($query);
        } elseif ($category == "muutlehdet") {
            $query = "SELECT image FROM " . $this->db_table6;
            $result = $this->db->getDb()->prepare($query);
        } elseif ($category == "kaikkilehdet") {
            $query = "SELECT image FROM " . $this->db_table7;
            $result = $this->db->getDb()->prepare($query);
        }
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }
        return $json;
    }

    public function getAllMagazineNames() {
        $query = "SELECT magazine_name FROM " . $this->db_table7;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        return $results;
    }

    public function addMagazineLike($magazine_name) {
        $query = "UPDATE " . $this->db_table7 . " SET likes = likes + 1 WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine_name);
        $result->execute();

        if ($result == true) {
            $json = 1;
            return $json;
        } else {
            $json = 0;
            return $json;
        }
    }

    public function removeMagazineLike($magazine_name) {
        $query = "UPDATE " . $this->db_table7 . " SET likes = likes - 1 WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine_name);
        $result->execute();

        if ($result == true) {
            $json = 1;
            return $json;
        } else {
            $json = 0;
            return $json;
        }
    }

    public function getMagazineLikes() {
        $json['likes'] = array();
        $query = "select likes, magazine_name from " . $this->db_table7;
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

    public function getMagazineIdent($magazine_name) {
        $query = "SELECT id FROM " . $this->db_table7 . " WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazine_name);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $id = $result->id;
        }

        return $id;
    }

    public function getMagName($id) {
        $query = "SELECT magazine_name FROM " . $this->db_table7 . " WHERE id = :id";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':id', $id);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $name = $result->magazine_name;
        }

        return $name;
    }

    public function getMagazineImageLikesByName($magazinename) {
        $query = "SELECT image, likes, magazine_name FROM " . $this->db_table7 . " WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazinename);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['image'] = $result->image;
            $json['likes'] = $result->likes;
            $json['name'] = $result->magazine_name;
        }
        return $json;
    }

}
