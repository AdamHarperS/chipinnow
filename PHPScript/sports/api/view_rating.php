<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();
$sql = " SELECT tr.*,tu.user_name, tuu.user_name as coach_name FROM tbl_rating tr inner join tbl_user tu on"
         . " tu.id = tr.user_id inner join tbl_user tuu on tuu.id = tr.coach_id where tr.coach_id =  ".$_GET['coach_id'];
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {
        $arrTmp[] = $row;
    }
    if (!empty($arrTmp)) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['view_rating'] = $arrTmp;
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['view_rating'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['view_rating'] = $no_record;
}

echo json_encode($arrRecord);
?>