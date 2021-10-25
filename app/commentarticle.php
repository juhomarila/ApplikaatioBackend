<?php

require_once '../comments.php';

$commentObject = new Comments();

if (isset($_POST['email'])) {
    
    $email = $_POST['email'];
    
}
if (isset($_POST['article_name'])) {
    
    $articlename = $_POST['article_name'];
    
}
if (isset($_POST['comment'])) {
    
    $comment = $_POST['comment'];
    
}

$data = $commentObject->commentArticle($email, $articlename, $comment);

echo json_encode($data);

?>