<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php"; 
$arrRecord = array();
// $sql = "SELECT tp.id, photo,user_id as coach_id,  experience, tc.category_name, tu.user_name as coach_name FROM tbl_trainer_profile tp"
//         . " inner join tbl_category tc on tc.id = tp.sport_id"
//         . " inner join tbl_user tu on tu.id = tp.user_id"
//         . " order by likes desc  limit 10";

$sql = "SELECT tp.id, photo,user_id as coach_id, tc.category_name, tu.user_name as coach_name FROM tbl_trainer_profile tp"
        . " inner join tbl_category tc on tc.id = tp.sport_id"
        . " inner join tbl_user tu on tu.id = tp.user_id"
        . " limit 10";
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {

        $hours=0;
        $sqlCourse = "SELECT * FROM  tbl_orders  WHERE trainer_id = " . $row['coach_id'] . " order by id desc";
        //echo $sqlCourse;
        $rowsCourse = $db->query($sqlCourse)->fetch();

        // if ($db->affected_rows > 0) {
        //     $date1 = new DateTime($rowsCourse[0]['last_booking_time']);
        //     $date2 = new DateTime(date("Y-m-d h:i:s"));
        //     $diff = $date2->diff($date1);
        //     $hours = $diff->h;
        //     $hours = $hours + ($diff->days * 24);
            
        // }
        // $row['last_booking_time'] = $hours . " hour ago";
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