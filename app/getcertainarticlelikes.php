<?php

require_once '../article.php';

$articleObject = new Article();

$image = "";

if (isset($_POST['image'])) {

    $image = $_POST['image'];
}

$likes = $articleObject->getCertainArticleLikes($image);

echo json_encode($likes);

?>