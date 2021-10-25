<?php

require_once '../magazine.php';

$image = "";

if (isset($_POST['image'])) {

    $image = $_POST['image'];
}

$magazineObject = new Magazine();

$category = $magazineObject->getCategory($image);

$data = $magazineObject->getMagazineInfo($image, $category);

echo json_encode($data);

?>