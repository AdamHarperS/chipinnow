<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();
$sql = "SELECT id, category_name, image, viewed, liked FROM tbl_category";
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
        $arrRecord['data']['category'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category'] = 'no record found';
}

echo json_encode($arrRecord);
?>