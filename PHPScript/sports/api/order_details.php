<?php
include "../config.inc.php";
include "error_response.php";
$arrRecord = array();

if (isset($_GET["order_id"])) {
    
    $query = mysqli_query($conn, "SELECT tor.id , tor.trainer_id , tor.height , tor.weight , tor.age , tor.user_id , tor.status , tc.course_name , tc.course_detail , tc.course_price , tc.id as course_id , count(tor.course_id) as total_seat , tc.course_location, tc.image , tc.allowed_trainee , tu.user_name , tu.email , tca.category_name  from tbl_orders tor 
    inner join tbl_course tc on tc.id = tor.course_id
    inner join tbl_user tu on tc.trainer_id = tu.id
    inner join tbl_category tca on tc.course_category = tca.id
    where tor.id='".$_REQUEST['order_id']."'");

    while ($res = mysqli_fetch_array($query))
    {

        $totalSeat = 0;
        $sqlOrder = "SELECT count(*) as total_seat FROM tbl_orders WHERE course_id = " . $res["course_id"] . " and status!='Cancel'";

        $rowsOrder = $db->query($sqlOrder)->fetch();
        if (isset($rowsOrder[0]["total_seat"])) {
            $totalSeat = $rowsOrder[0]["total_seat"];
        }
        
        $trainee_id = $res['user_id'];

        $sqltrainee = mysqli_query($conn,"SELECT * FROM tbl_user WHERE id = " . $trainee_id . "");
        $restrainee = mysqli_fetch_array($sqltrainee);

        $trainee_name = $restrainee['user_name'];
        $trainee_contact = $restrainee['user_mobile'];

        $row = $res["allowed_trainee"] - $totalSeat;
        $seats_left = $row;
        $str = $res['age'];
        $age = explode('.', $str);
        $year = $age[0];
        $month = $age[1];

        $data= array(
            "course_name" => $res['course_name'],
            "coach_name" => $res['user_name'],
            "category_name" => $res['category_name'],
            "course_detail" => $res['course_detail'],
            "seats_left" => $seats_left,
            "course_price" => $res['course_price'],
            "course_address" => $res['course_location'],
            "course_photo" => $res['image'],
            "status" => $res['status'],
            "allowed_trainee" => $res['allowed_trainee'],
            "trainee_name" => $trainee_name,
            "year" => $year,
            "month" => $month,
            "height" => $res['height'],
            "weight" => $res['weight'],
            "mobile" => $trainee_contact,
            "email" => $res['email']
        );
        $data1=array();
    }
        if (isset($data)) {
            if (!empty($data)) {
            $arrRecord['data']['success'] = 1;
                $arrRecord['data']['order_id'] = $data;
            } else {
                $arrRecord['data']['success'] = 0;
                $arrRecord['data']['order_id'] = $no_record;
            }
}
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['order_id'] = $empty_user;
}
echo json_encode($arrRecord);
//echo '<pre>',print_r($arrRecord,1),'</pre>';
?>