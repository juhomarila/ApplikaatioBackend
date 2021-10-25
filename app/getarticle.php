<?php

require_once '../article.php';

$image = "";

$articleObject = new Article();

if (isset($_POST['image'])) {

    $image = $_POST['image'];
}

if (!empty($image)) {

    $data = $articleObject->getArticle($image);

    echo json_encode($data);
}
?>

