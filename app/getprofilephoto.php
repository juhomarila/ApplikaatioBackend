<?php

require_once '../user.php';

$email = "";

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

$userObject = new User();

if (!empty($email)) {
    $img = $userObject->getProfilePhoto($email);
    $json_array['photoID'] = $img;
    echo json_encode($json_array);
}


