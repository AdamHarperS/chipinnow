<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

if (isset($_GET["trainerid"])) {
    $record_id = strip_tags($_GET["trainerid"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

        $sql = "SELECT * FROM tbl_trainer_profile WHERE user_id = " . $record_id;
        $rows = $db->query($sql)->fetch();

        $sqlOrder = "SELECT( SELECT count(*) FROM tbl_orders WHERE trainer_id= " . $record_id . " and status='Accepted') as accepted,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $record_id . " and status='Cancel') as canceled,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $record_id . " and status='Pending') as pending,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $record_id . " and status='Disapproved') as disapprove,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $record_id . " and status='Completed') as complete";
        $rowsOrder = $db->query($sqlOrder)->fetch();
        if ($db->affected_rows > 0) {

            if (!empty($rowsOrder)) {
                $rows[0]['accepted'] = $rowsOrder[0]['accepted'];
                $rows[0]['canceled'] = $rowsOrder[0]['canceled'];
                $rows[0]['pending'] = $rowsOrder[0]['pending'];
                $rows[0]['disapprove'] = $rowsOrder[0]['disapprove'];
                $rows[0]['complete'] = $rowsOrder[0]['complete'];
            }
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['general'] = $rows;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['general'] = $no_record;
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['general'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['general'] = $no_record;
}

echo json_encode($arrRecord);
?>