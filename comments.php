<?php

include_once 'db-connect.php';
require_once 'user.php';
require_once 'article.php';

class Comments {

    private $db;
    private $db_table = "comments";
    private $db_table2 = "users";
    private $db_table3 = "kaikkiartikkelit";

    public function __construct() {

        $this->db = new DbConnect();
    }

    public function commentArticle($email, $articlename, $comment) {
        $userobject = new User();
        $articleobject = new Article();
        $id = $userobject->getUserId($email);
        $artId = $articleobject->getArticleId($articlename);
        $commentid = md5(uniqid($email, true));
        $query = "insert into " . $this->db_table . " (user_id, article_id, comment_id, comment, comment_date) values (:user_id, :article_id, :comment_id, :comment, NOW())";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':article_id', $artId);
        $result->bindParam(':comment_id', $commentid);
        $result->bindParam(':comment', $comment);

        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
            $json['commentid'] = $commentid;
        } else {
            $json['success'] = 0;
        }
        return $json;
    }

    public function removeArticleComment($email, $articlename) {
        $userobject = new User();
        $articleobject = new Article();
        $id = $userobject->getUserId($email);
        $artId = $articleobject->getArticleId($articlename);
        $query = "delete from " . $this->db_table . " where user_id = :user_id AND article_id = :article_id AND comment_id = :comment_id";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->bindParam(':article_id', $artId);
        $result->bindParam(':comment_id', $artId);

        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
        } else {
            $json['success'] = 0;
        }
        return $json;
    }

    public function getAllArticleComments($articlename) {
        $json['comments'] = array();
        $userobject = new User();
        $articleobject = new Article();
        $artId = $articleobject->getArticleId($articlename);
        $query = "SELECT comment, user_id, comment_date  FROM " . $this->db_table . " WHERE article_id = :article_id ORDER BY comment_date DESC";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':article_id', $artId);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $results[$i]);
        }

        $json2['comments'] = array();

        for ($i = 0; $i < sizeof($json) - 1; $i++) {
            $data2['name'] = $userobject->getFirstLastname($json[$i]->user_id);
            $data2['comment_date'] = $json[$i]->comment_date;
            $data2['comment'] = $json[$i]->comment;
            array_push($json2, $data2);
        }
        return $json2;
    }

}
