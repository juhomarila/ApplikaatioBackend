<?php

require_once '../user.php';
require_once '../magazine.php';

$email = "";

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

$userObject = new User();
$magazineObject = new Magazine();
$likes = $userObject->getLikes($email);
$json['favourites'] = array();

for ($i = 0; $i < sizeof($likes) - 1; $i++) {
    array_push($json, $magazineObject->getMagazineImageLikesByName($likes[$i]));
}
echo json_encode($json);

?>