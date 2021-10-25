<?php

require_once '../user.php';

$email = "";

$password = "";

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

if (isset($_POST['password'])) {

    $password = $_POST['password'];
}

$userObject = new User();

$firstname = $userObject->getFirstname($email);
$lastname = $userObject->getLastname($email);

// Login

if (!empty($email) && !empty($password)) {

    $verify = $userObject->passwordVerify($email, $password);

    $json_array = $userObject->loginUsers($email, $verify);
    $json_array['lastname'] = $lastname;
    $json_array['firstname'] = $firstname;

    echo json_encode($json_array);
}
?>

