<?php

require_once '../magazine.php';

$magazineObject = new Magazine();

$likes = $magazineObject->getMagazineLikes();

echo json_encode($likes);

?>