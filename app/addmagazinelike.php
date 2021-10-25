<?php

require_once '../magazine.php';
require_once '../likes.php';

$name = "";
$email = "";

if (isset($_POST['magazine_name'])) {

    $name = $_POST['magazine_name'];
}

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

$magazineObject = new Magazine();
$likesObject = new Likes();
$liked = $likesObject->checkIfMagazineLiked($email, $name);

if ($liked == true) {
    $json_array['liked'] = $liked;
} else {
    $data = $magazineObject->addMagazineLike($name);
    $likesObject->likeMagazine($email, $name);
    $json_array['liked'] = $data;
}
echo json_encode($json_array);
?>