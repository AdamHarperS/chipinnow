<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();

if (isset($_GET["trainerid"])) {
    $record_id = strip_tags($_GET["trainerid"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

        $sql = "SELECT * FROM tbl_trainer_profile WHERE user_id = " . $record_id;
        $rows = $db->query($sql)->fetch();

        $sqlOrder = "SELECT( SELECT count(*) FROM tbl_orders WHERE trainer_id= " . $record_id . " and status='Approve') as accepted,"
                . " ( SELECT count(*) FROM tbl_orders WHERE user_id=" . $record_id . " and status='Cancel') as canceled";
        $rowsOrder = $db->query($sqlOrder)->fetch();
        if ($db->affected_rows > 0) {

            if (!empty($rowsOrder)) {
                $rows[0]['accepted'] = $rowsOrder[0]['accepted'];
                $rows[0]['canceled'] = $rowsOrder[0]['canceled'];
            }
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['general'] = $rows;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['general'] = 'no record found';
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['general'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['general'] = 'no record found';
}

echo json_encode($arrRecord);
?>