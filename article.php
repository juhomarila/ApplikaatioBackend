<?php

include_once 'db-connect.php';

class Article {

    private $db;
    private $db_table = "kaikkiartikkelit"; //tee db joka lehdelle erikseen tai sitten renameta kaikkiartikkelit
    private $db_table2 = "tiedelehdet";
    private $db_table3 = "urheilulehdet";
    private $db_table4 = "ruokalehdet";
    private $db_table5 = "muutlehdet";

    public function __construct() {

        $this->db = new DbConnect();
    }

    /// tänne tehtävä kaikkea että uppii oman lehtensä db:seen myös, koska siten saa kaikki tykkäykset jne.
    public function uploadArticle($image, $text, $author, $date, $name, $subtitle, $magazine_name) {
        $query = "insert into " . $this->db_table . " (name, subtitle, author, date, image, text, magazine_name) values (:name, :subtitle, :author, :date, :image, :text, :magazine_name)";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
        $result->bindParam(':subtitle', $subtitle);
        $result->bindParam(':author', $author);
        $result->bindParam(':date', $date);
        $result->bindParam(':image', $image);
        $result->bindParam(':text', $text);
        $result->bindParam(':magazine_name', $magazine_name);

        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
            $json['message'] = "Successfully uploaded article";
        } else {
            $json['success'] = 0;
            $json['message'] = "Vittu";
        }
        $this->db = null;
        return $json;
    }

    public function getArticle($image) {
        $query = "SELECT * FROM " . $this->db_table . " WHERE image = :image";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':image', $image);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['name'] = $result->name;
            $json['subtitle'] = $result->subtitle;
            $json['author'] = $result->author;
            $json['date'] = $result->date;
            $json['image'] = $result->image;
            $json['text'] = $result->text;
        }

        return $json;
    }

    public function getAllImages() {
        $json['images'] = array();
        $query = "SELECT image FROM " . $this->db_table;
        $result = $this->db->getDb()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }
        //echo count($json);
        return $json;
    }

    public function getArticleName($category, $image) {
        $json = array();
        if ($category == "tiedelehdet") {
            $query = "SELECT magazine_name FROM " . $this->db_table2 . " WHERE image = :image";
        } elseif ($category == "urheilulehdet") {
            $query = "SELECT magazine_name FROM " . $this->db_table3 . " WHERE image = :image";
        } elseif ($category == "ruokalehdet") {
            $query = "SELECT magazine_name FROM " . $this->db_table4 . " WHERE image = :image";
        } elseif ($category == "muutlehdet") {
            $query = "SELECT magazine_name FROM " . $this->db_table5 . " WHERE image = :image";
        }
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':image', $image);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['magazine_name'] = $result->magazine_name;
        }

        return $json;
    }

    public function getImageWithName($magazinename, $category) {
        if ($category == "tiedelehdet") {
            $query = "SELECT image FROM " . $this->db_table2 . " WHERE magazine_name = :magazine_name";
        } elseif ($category == "urheilulehdet") {
            $query = "SELECT image FROM " . $this->db_table2 . " WHERE magazine_name = :magazine_name";
        } elseif ($category == "ruokalehdet") {
            $query = "SELECT image FROM " . $this->db_table2 . " WHERE magazine_name = :magazine_name";
        } elseif ($category == "muutlehdet") {
            $query = "SELECT image FROM " . $this->db_table2 . " WHERE magazine_name = :magazine_name";
        }
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazinename);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['image'] = $result->image;
        }

        return $json;
    }

    public function articleCount($magazinename) {
        $json['dates'] = array();
        //$query = "SELECT COUNT(*) FROM " . $this->db_table . " WHERE magazine_name = :magazine_name";
        $query = "SELECT date FROM " . $this->db_table . " WHERE magazine_name = :magazine_name ORDER BY date DESC";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazinename);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }
        return $json;
    }

    public function getArticleImagesTitlesByMonth($month, $magazine_name) {
        $json['articles'] = array();
        $query = "SELECT image, name FROM " . $this->db_table . " WHERE date LIKE :month AND magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':month', $month);
        $result->bindParam(':magazine_name', $magazine_name);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }
        return $json;
    }

    public function getAllArticles($magazinename) {
        $json['articles'] = array();
        $query = "SELECT * FROM " . $this->db_table . " WHERE magazine_name = :magazine_name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':magazine_name', $magazinename);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }
        return $json;
    }

    public function addArticleLike($name) {
        $query = "UPDATE " . $this->db_table . " SET likes = likes + 1 WHERE name = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $name);
        $result->execute();

        if ($result == true) {
            $json = 1;
            return $json;
        } else {
            $json = 0;
            return $json;
        }
    }

    public function getArticleLikes() {
        $json['likes'] = array();
        $query = "select likes, name from " . $this->db_table;
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

    public function getCertainArticleLikes($image) {

        $query = "select likes from " . $this->db_table . " WHERE image = :image";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':image', $image);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $json['likes'] = $result->likes;
        }

        return $json;
    }

    public function getArticleId($articlename) {
        $query = "SELECT id FROM " . $this->db_table . " WHERE name = :name";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':name', $articlename);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $id = $result->id;
        }

        return $id;
    }

    public function getArticleComments() {
        $json['comments'] = array();
        $query = "select comments, image from " . $this->db_table;
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
