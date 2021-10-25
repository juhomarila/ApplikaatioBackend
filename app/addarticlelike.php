<?php

require_once '../article.php';
require_once '../likes.php';

$name = "";
$email = "";

if (isset($_POST['name'])) {

    $name = $_POST['name'];
}

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

$articleObject = new Article();
$likesObject = new Likes();
$liked = $likesObject->checkIfArticleLiked($email, $name);

if ($liked == true) {
    $json_array['liked'] = $liked;
} else {
    $data = $articleObject->addArticleLike($name);
    $likesObject->likeArticle($email, $name);
    $json_array['liked'] = $data;
}
echo json_encode($json_array);

?>