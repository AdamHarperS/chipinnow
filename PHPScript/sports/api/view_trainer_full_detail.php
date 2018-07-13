<?php
include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

if (isset($_GET["coachid"])) {
    $record_id = strip_tags($_GET["coachid"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

        $sql = "SELECT tp.id, tp.photo, tp.video, tp.city, tp.experience, tp.qualification, "
                . "tp.achievement, tp.license, tp.joining_date, tp.college,tp.about_coach,"
                . " tu.user_name, tcc.course_location as address"
                . " FROM tbl_trainer_profile tp left join tbl_user tu on tu.id = tp.user_id"
                . " left join tbl_category tc on tc.id = tp.sport_id "
                . " left join tbl_course tcc on tcc.trainer_id = tp.user_id "
                . "WHERE user_id = " . $record_id;

        // $sql = "SELECT tp.id, tp.age, tp.level, tp.photo, tp.video, tp.experience, tp.qualification, "
        //         . "tp.achievement, tp.license, tp.joining_date, tp.college,tp.about_coach,"
        //         . " tp.location,tu.user_name, tu.email, tcc.course_location as address,tcc.hours_number as number_of_hours, tc.category_name"
        //         . " FROM tbl_trainer_profile tp inner join tbl_user tu on tu.id = tp.user_id"
        //         . " inner join tbl_category tc on tc.id = tp.sport_id "
        //         . " inner join tbl_course tcc on tcc.trainer_id = tp.user_id "
        //         . "WHERE user_id = " . $record_id;
       
        $rows = $db->query($sql)->fetch();
        

        $sqlOrder = "SELECT( SELECT count(*) FROM tbl_orders WHERE user_id= " . $record_id . " and status='Approve') as accepted,"
                . " ( SELECT count(*) FROM tbl_orders WHERE user_id=" . $record_id . " and status='Cancel') as canceled";
        $rowsOrder = $db->query($sqlOrder)->fetch();

        if ($db->affected_rows > 0) {

            // if (!empty($rowsOrder)) {
            //     $rows[0]['accepted'] = $rowsOrder[0]['accepted'];
            //     $rows[0]['canceled'] = $rowsOrder[0]['canceled'];
            // }
            $arrGallery = array();
            $sqlGallery = "SELECT * FROM tbl_gallery WHERE trainer_id= " . $record_id;
            //echo $sqlGallery;
            $rowsGallery = $db->query($sqlGallery)->fetch();

            if ($db->affected_rows > 0) {
                foreach ($rowsGallery as $valueGallery) {
                    
                    $arrGallery[]['images'] = $valueGallery['image'];
                }

            }
            else {
                    $arrGallery;
            }

                $rows[0]['gallery'] = $arrGallery;

            // $arrCourse = array();
            // $sqlCourse = "SELECT tc.*, tor.order_date as last_booking_time , tc.trainer_id as coach_id FROM tbl_course tc inner join tbl_orders tor on tor.trainer_id = tc.trainer_id"
            //         . " WHERE tc.trainer_id  = " . $record_id . " group by tor.course_id DESC";
            // //echo $sqlCourse;
            // $rowsCourse = $db->query($sqlCourse)->fetch();

            // if ($db->affected_rows > 0) {
            //     foreach ($rowsCourse as $valueCourse) {

            //         $date1 = new DateTime($valueCourse['last_booking_time']);
            //         $date2 = new DateTime(date("Y-m-d h:i:s"));
            //         $diff = $date2->diff($date1);
            //         $hours = $diff->h;
            //         $hours = $hours + ($diff->days * 24);
            //         $valueCourse['last_booking_time'] = $hours. " hour ago";
            //         $arrCourse[]['course'] = $valueCourse;
            //     }
            // }
            // $rows[0]['course_list'] = $arrCourse;
                
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['general'] = $rows[0];
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
// /echo '<pre>',print_r($arrRecord,1),'</pre>';
?>