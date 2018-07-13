<?php
include "../config.inc.php";
include 'error_response.php';

if (isset($_GET["coachid"])) {
    $query = mysqli_query($conn, "SELECT tc.allowed_trainee , tc.course_location , tc.course_city , tc.course_price , tc.course_detail , tc.course_name , tc.trainer_id , tc.id as course_id, tp.experience , tc.hours_number , tp.user_id , tp.age , tp.sport_id , tp.photo , tca.category_name , tu.user_name , tu.gender from 
        tbl_course tc inner join tbl_trainer_profile tp on tc.trainer_id = tp.user_id 
        inner join tbl_category tca on tp.sport_id = tca.id
        inner join tbl_user tu on tc.trainer_id = tu.id
        WHERE tc.trainer_id = '".$_REQUEST['coachid']."'");

    $query_r = mysqli_query($conn, "select avg(rating) as rating from tbl_rating where user_id='".$_REQUEST['coachid']."'");
    $rating = mysqli_fetch_array($query_r);
    $rat = $rating['rating'];

    // $query = mysqli_query($conn, "SELECT tc.allowed_trainee , tc.course_location , tc.course_city , tc.course_price , tc.course_detail , tc.course_name , tc.trainer_id , tc.id as course_id, tp.experience , tc.hours_number , tp.user_id , tp.age , tp.sport_id , tp.photo , tca.category_name , tu.user_name , tu.gender ,  count(tor.course_id) as total_seat , avg( tr.rating ) as rating from 
    //     tbl_course tc inner join tbl_trainer_profile tp on tc.trainer_id = tp.user_id 
    //     inner join tbl_category tca on tp.sport_id = tca.id
    //     inner join tbl_user tu on tc.trainer_id = tu.id
    //     left join tbl_orders tor on tor.trainer_id = tc.trainer_id
    //     left join tbl_rating tr on tr.coach_id = tp.user_id
    //     WHERE tc.trainer_id = '".$_REQUEST['coachid']."' ");



    while ($res = mysqli_fetch_array($query))
    {
        $data[] = array(
            "allowed_trainee" => $res['allowed_trainee'],
            "category_name" => $res['category_name'],
            "coach_name" => $res['user_name'],
            "coach_photo" => $res['photo'],
            "course_detail" => $res['course_detail'],
            "course_id" => $res['course_id'],
            "course_city" => $res['course_city'],
            "course_address" => $res['course_location'],
            "course_name" => $res['course_name'],
            "course_price" => $res['course_price'],
            "id" => $res['course_id'],
            "rating" => $rat,
            "coach_id" => $res['trainer_id']
        );
        $data1=array();
    }
        
        if (isset($data)) {
            if (!empty($data)) {
                $arrRecord['success'] = "1";
                $arrRecord['category'] = $data;
            } else {
                $arrRecord['success'] = "0";
                $arrRecord['category'] = $no_record;
            }
        } else {
            $arrRecord['success'] = "0";
            $arrRecord['category'] = $no_record;
        }
    } else {
        $arrRecord['success'] = "0";
        $arrRecord['gallery'] = $no_record;
    }
    echo json_encode($arrRecord);

?>