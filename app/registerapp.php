<?php

require_once '../user.php';

$firstname = "";

$lastname = "";

$password = "";

$email = "";

if (isset($_POST['firstname'])) {

    $firstname = $_POST['firstname'];
}

if (isset($_POST['lastname'])) {

    $lastname = $_POST['lastname'];
}

if (isset($_POST['password'])) {

    $password = $_POST['password'];
}

if (isset($_POST['email'])) {

    $email = $_POST['email'];
}

$userObject = new User();

// Registration

if (!empty($firstname) && !empty($lastname) && !empty($password) && !empty($email)) {

    $json_registration = $userObject->confirmRegistration($email, $firstname, $lastname, $password);

    echo json_encode($json_registration);
}

?>

