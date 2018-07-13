<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

$arrRecord = array();
$arrError = array();

if (!isset($_GET['search'])) {
    $arrError[] = "Search is not provided";
} else {
    if (empty($_GET['search'])) {
        $arrError = $no_record;
    }
}


if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['search_result'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {

    $arrTemp = array();

    $sqlSelect= "SELECT tc.id, tc.trainer_id, tc.course_name, tc.course_detail , tc.course_city , tc.id as course_id, tc.course_price,"
        . " tc.course_location,tc.hours_number as number_of_hours, tca.category_name, "
        . " tu.user_name as coach_name, tu.gender, tc.image as course_photo, tc.allowed_trainee, "
        . " tp.experience, tp.sport_id, tp.age FROM tbl_course tc "
        . " inner join tbl_user tu on tu.id = tc.trainer_id"
        . " inner join tbl_trainer_profile tp on tp.user_id = tu.id "
        . " inner join tbl_category tca on tca.id = tp.sport_id"
        . " where tc.course_name like '%".$_GET['search']."%'";

    $rows = $db->query($sqlSelect)->fetch();

    foreach ($rows as $row) {
        $rating = 0;
        $sqlRating = "SELECT count(rating) as totalRating, IFNULL(SUM(rating),0) as sumRating FROM tbl_rating 
        WHERE coach_id=" . $row["trainer_id"] . "";

        $rowsRaing = $db->query($sqlRating)->fetch();
        if ($db->affected_rows > 0) {
            if (!empty($rowsRaing)) {
                $record = $rowsRaing[0];
                if ($record["sumRating"] > 0 && $record["totalRating"] > 0) {
                    $rating = $record["sumRating"] / $record["totalRating"];
                }
            }
        }
        $row['rating'] = $rating;

        $arrTemp[] = $row;
    }


    //if ($db->affected_rows['course_name'] == 'null') {
    if ($db->affected_rows > 0) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['search_result'] = $arrTemp;
    } else {
        $arrRecord['data']['success'] = 'null';
        $arrRecord['data']['search_result'] = $no_record;
    }
}


echo json_encode($arrRecord);
//echo '<pre>',print_r($arrRecord,1),'</pre>';
?>