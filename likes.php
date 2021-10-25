<?php

include_once 'db-connect.php';
require_once 'user.php';
require_once 'magazine.php';
require_once 'article.php';

class Likes {

    private $db;
    private $db_table = "likes";
    private $db_table2 = "users";
    private $db_table3 = "kaikkilehdet";
    private $db_table4 = "kaikkiartikkelit";

    public function __construct() {

        $this->db = new DbConnect();
    }

    public function checkIfMagazineLiked($email, $magazinename) {
        $userobject = new User();
        $magazineobject = new Magazine();
        $id = $userobject->getUserId($email);
        $magId = $magazineobject->getMagazineIdent($magazinename);
        $query = "select * from " . $this->db_table . " where user_id = :user_id AND magazine_id = :magazine_id Limit 1";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':magazine_id', $magId);
        $result->execute();
        $rows = $result->rowCount();

        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function likeMagazine($email, $magazinename) {
        $userobject = new User();
        $magazineobject = new Magazine();
        $id = $userobject->getUserId($email);
        $magId = $magazineobject->getMagazineIdent($magazinename);
        $query = "insert into " . $this->db_table . " (user_id, magazine_id) values (:user_id, :magazine_id)";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':magazine_id', $magId);

        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
        } else {
            $json['success'] = 0;
        }
        return $json;
    }

    public function removeMagazineLike($email, $magazinename) {
        $userobject = new User();
        $magazineobject = new Magazine();
        $id = $userobject->getUserId($email);
        $magId = $magazineobject->getMagazineIdent($magazinename);
        $query = "delete from " . $this->db_table . " where user_id = :user_id AND magazine_id = :magazine_id";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':magazine_id', $magId);

        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
        } else {
            $json['success'] = 0;
        }
        return $json;
    }

    public function checkIfArticleLiked($email, $articlename) {
        $userobject = new User();
        $articleobject = new Article();
        $id = $userobject->getUserId($email);
        $artId = $articleobject->getArticleId($articlename);
        $query = "select * from " . $this->db_table . " where user_id = :user_id AND article_id = :article_id Limit 1";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':article_id', $artId);
        $result->execute();
        $rows = $result->rowCount();

        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function likeArticle($email, $articlename) {
        $userobject = new User();
        $articleobject = new Article();
        $id = $userobject->getUserId($email);
        $artId = $articleobject->getArticleId($articlename);
        $query = "insert into " . $this->db_table . " (user_id, article_id) values (:user_id, :article_id)";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':article_id', $artId);

        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
        } else {
            $json['success'] = 0;
        }
        return $json;
    }

}
