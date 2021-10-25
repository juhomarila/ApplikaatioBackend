<?php

require_once '../user.php';

$email = "";

$image = "";

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

if (isset($_POST['image'])) {

    $image = $_POST['image'];
}

$userObject = new User();

if (!empty($_POST['image'] && $_POST['email'])) {
    // Get file info 
    $insert = $userObject->updateProfilePhoto($email, $image);
}

echo json_encode($insert);
