<?php

require_once '../article.php';

$articleObject = new Article();

$likes = $articleObject->getArticleLikes();

echo json_encode($likes);

?>