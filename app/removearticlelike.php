<?php

require_once '../magazine.php';
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

$data = $articleObject->removeArticleLike($name);
$likesObject->removeArticleLike($email, $name);
$json_array['liked'] = $data;

echo json_encode($json_array);
?>