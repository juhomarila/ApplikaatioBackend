<?php

require_once '../comments.php';
require_once '../user.php';

$commentObject = new Comments();
$userObject = new User();

if (isset($_POST['article_name'])) {
    $articlename = $_POST['article_name'];
}

$json['comments'] = array();

$comments = $commentObject->getAllArticleComments($articlename);
//for ($i = 0; $i < sizeof($data) - 1; $i++) {
//    $data2['name'] = $userObject->getFirstLastname($data[$i]->user_id);
//    $data2['comment_date'] = $data[$i]->comment_date;
//    $data2['comment'] = $data[$i]->comment;
//    array_push($json, $data2);
//}

echo json_encode($comments);
?>