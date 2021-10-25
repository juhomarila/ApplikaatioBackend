<?php

require_once '../offlinedata.php';

$offlineObject = new OfflineData();

$alldata['alldata'] = array();
$data = $offlineObject->loadArticles();
array_push($alldata, $data);
$data1 = $offlineObject->loadMagazines();
array_push($alldata, $data1);
$data2 = $offlineObject->otherMagazines();
array_push($alldata, $data2);
$data3 = $offlineObject->foodMagazines();
array_push($alldata, $data3);
$data4 = $offlineObject->scienceMagazines();
array_push($alldata, $data4);
$data5 = $offlineObject->sportMagazines();
array_push($alldata, $data5);
$data6 = $offlineObject->loadAuthors();
array_push($alldata, $data6);

//for ($i = 0; $i < count($alldata) - 1; $i++) {
//    //print_r($alldata[$i]);
//    //echo "paskaa";
//    for ($k = 0; $k < count($alldata[$i]) - 1; $k++) {
//        print_r($alldata[$i][$k]->subtitle);
//    }
//}



echo json_encode($alldata);
?>

