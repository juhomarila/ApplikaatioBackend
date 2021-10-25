<?php

require_once '../article.php';

$articleObject = new Article();

$comments = $articleObject->getArticleComments();

echo json_encode($comments);

?>