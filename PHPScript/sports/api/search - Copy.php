<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();

$arrRecord = array();
$arrError = array();

if (!isset($_GET['search'])) {
    $arrError[] = "Search is not provided";
} else {
    if (empty($_GET['search'])) {
        $arrError[] = "Did not get any keyword";
    }
}


if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['search_result'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {

    $arrTemp = array();
    $sqlSelect = "SELECT  tu.id, tu.user_name, tp.experience, tp.photo, tc.category_name,"
            . " ( SELECT od_date FROM tbl_orders tbo WHERE tbo.trainer_id=tu.id  order by id desc limit 1) as last_booking_time"
            . "  FROM tbl_user tu inner join tbl_trainer_profile tp on tp.user_id = tu.id inner join tbl_category"
            . " tc on tc.id= tp.sport_id WHERE"
            . " tu.user_name like '%" . $_GET['search'] . "%' and tu.usertype='trainer'";

//    $sqlSelect = "SELECT  tu.id, tu.user_name,tp.views, tp.likes, tp.experience, tp.photo, tc.category_name,"
//            . " ( SELECT order_date FROM tbl_orders tbo WHERE tbo.trainer_id=tu.id and tbo.status='Approve' order by id desc limit 1) as order_date"
//            . "  FROM tbl_user tu inner join tbl_trainer_profile tp on tp.user_id = tu.id inner join tbl_category"
//            . " tc on tc.id= tp.sport_id WHERE"
//            . " tu.user_name like '%" . $_GET['search'] . "%' and tu.usertype='trainer'";

    $rows = $db->query($sqlSelect)->fetch();

    foreach ($rows as $row) {


        $date1 = new DateTime($row['last_booking_time']);
        $date2 = new DateTime(date("Y-m-d h:i:s"));
        $diff = $date2->diff($date1);
        $hours = $diff->h;
        $hours = $hours + ($diff->days * 24);
        $row['last_booking_time'] = $hours . " hour ago";

        $arrTemp[] = $row;
    }

    if ($db->affected_rows > 0) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['search_result'] = $arrTemp;
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['search_result'] = 'no record found';
    }
}


echo json_encode($arrRecord);
?>