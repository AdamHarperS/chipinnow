<?php
include "../config.inc.php";
include "error_response.php";
if (isset($_GET["courseid"])) {
mysqli_set_charset($conn,'utf8');
    $query = mysqli_query($conn, "SELECT tc.allowed_trainee , tc.course_location , tc.course_price , tc.course_detail , tc.course_name , tc.trainer_id ,tc.image , tc.id as course_id, tp.experience , tc.hours_number , tp.user_id , tp.age , tp.sport_id , tp.photo , tca.category_name , tu.user_name , tu.gender ,  count(tor.course_id) as total_seat , avg( tr.rating ) as rating from 
        tbl_course tc inner join tbl_trainer_profile tp on tc.trainer_id = tp.user_id 
        inner join tbl_category tca on tp.sport_id = tca.id
        inner join tbl_user tu on tc.trainer_id = tu.id
        left join tbl_orders tor on tor.trainer_id = tc.trainer_id
        left join tbl_rating tr on tr.coach_id = tp.user_id
        WHERE tc.id = '".$_REQUEST['courseid']."' group by tc.trainer_id");
    while ($res = mysqli_fetch_array($query))
    {
            // $date1 = new DateTime($res['order_date']);
            // $date2 = new DateTime(date("Y-m-d h:i:s"));
            // $diff = $date2->diff($date1);
            // $hours = $diff->h;
            // $hours = $hours + ($diff->days * 24);

        $totalSeat = 0;
        $sqlOrder = "SELECT count(*) as total_seat FROM tbl_orders WHERE course_id = " . $res["course_id"] . " and status!='Cancel'";

        $rowsOrder = $db->query($sqlOrder)->fetch();
        if (isset($rowsOrder[0]["total_seat"])) {
            $totalSeat = $rowsOrder[0]["total_seat"];
        }

        $row["total_seat"] = $res["allowed_trainee"] - $totalSeat;

        $seats_left = $row;
        $experience = mysqli_real_escape_string($conn, $res['experience']);


        $data= array(
            "age" => $res['age'],
            "allowed_trainee" => $res['allowed_trainee'],
            "category_name" => $res['category_name'],
            "coach_name" => $res['user_name'],
            "coach_photo" => $res['photo'],
            "course_detail" => $res['course_detail'],
            "course_id" => $res['course_id'],
            "course_location" => $res['course_location'],
            "course_name" => $res['course_name'],
            "course_price" => $res['course_price'],
            "course_photo" => $res['image'],
            "experience" =>$experience,
            "gender" => $res['gender'],
            "id" => $res['course_id'],
            "seats_left" => $row['total_seat'],
            "number_of_hours" => $res['hours_number'],
            "rating" => isset($res['rating'])?$res['rating']:0,
            "sport_id" => $res['sport_id'],
            "coach_id" => $res['trainer_id']
        );
    }
    //print_r($data);
    if (isset($data)) {
            $arrRecord['success'] = "1";
            $arrRecord['course_detail'] = $data;
        } else {
            $arrRecord['success'] = "0";
            $arrRecord['course_detail'] = $no_record;
        }
} else {
    $arrRecord['success'] = "0";
    $arrRecord['course_detail'] = $no_record;
}
    echo json_encode($arrRecord);
?>