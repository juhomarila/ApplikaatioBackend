<?php

require_once '../article.php';

$magazine_name = "";

$articleObject = new Article();

if (isset($_POST['magazine_name'])) {
    
    $magazine_name = $_POST['magazine_name'];
    
}

$data = $articleObject->articleCount($magazine_name);

echo json_encode($data);
?>

