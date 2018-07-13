<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();
$sql = "SELECT * FROM `tbl_trainer_profile` order by likes desc  limit 10";
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {
        $arrTmp[] = $row;
    }
    if (!empty($arrTmp)) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['category'] = $arrTmp;
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category'] = $no_record;
}

echo json_encode($arrRecord);
?>