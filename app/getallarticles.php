<?php

require_once '../article.php';

$name = "";

if (isset($_POST['magazine_name'])) {

    $name = $_POST['magazine_name'];
}

$articleObject = new Article();

$data = $articleObject->getAllArticles($name);

echo json_encode($data);

?>

