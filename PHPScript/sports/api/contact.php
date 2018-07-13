<?php

include "../config.inc.php";
require_once '../class.database.php';
include 'error_response.php';
$arrRecord = array();
$sql = "SELECT contact_us As contact FROM tbl_contact";
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    if (!empty($rows)) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['category'] = $rows[0]['contact'];
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category'] = $no_record;
}

// echo $arrRecord['data']['category'];die;
echo json_encode($arrRecord);
?>