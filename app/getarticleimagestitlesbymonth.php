<?php

require_once '../article.php';

$month = "";

if (isset($_POST['month'])) {

    $month = $_POST['month'];
}

if (isset($_POST['magazine_name'])) {

    $magazine_name = $_POST['magazine_name'];
}
$articleObject = new Article();

$data = $articleObject->getArticleImagesTitlesByMonth($month, $magazine_name);

echo json_encode($data);

?>

