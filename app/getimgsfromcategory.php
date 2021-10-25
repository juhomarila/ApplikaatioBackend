<?php

require_once '../magazine.php';

$category = "";

if (isset($_POST['category'])) {

    $category = $_POST['category'];
}

$magazineObject = new Magazine();

$data = $magazineObject->getImageFromCertainCategory($category);

echo json_encode($data);

?>

