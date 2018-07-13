<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();

$arrRecord = array();
$arrError = array();

function fillStatus($trainer_id, $status) {
    global $db;
    $total = 0;
    $sql = "SELECT count(*) as total FROM `tbl_orders` where trainer_id=" . $trainer_id . " and status='".$status."'";
    //echo $sql;
    $row = $db->query($sql)->fetch();
    if (count($row) > 0) {
        $total = $row[0]['total'];
    }
    return $total;
}

//exit();
if (!isset($_POST['trainer_id'])) {
    $arrError[] = "Coach ID is not provided";
} else {
    if (empty($_POST['trainer_id'])) {
        $arrError[] = "Coach ID is empty";
    }
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['request_order'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {

    $arrStatus = array(
        "Under Process" => 0,
        "Approve" => 0,
        "Disapprove" => 0,
        "Completed" => 0,
        "Cancel" => 0,
    );

    foreach ($arrStatus as $key=>$record) {
        $arrStatus[$key] = fillStatus($_POST['trainer_id'], $key);
    }

    if (count($arrStatus)>0) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['request_order'] = $arrStatus;
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['request_order'] = $arrStatus;
    }
}



echo json_encode($arrRecord);
?>