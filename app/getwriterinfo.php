<?php

require_once '../writer.php';

$name = "";

$writerObject = new Writer();

if (isset($_POST['name'])) {

    $name = $_POST['name'];
}

if (!empty($name)) {
    
    $data = $writerObject->getWriterInfo($name);

    echo json_encode($data);
}
?>

