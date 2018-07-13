<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();
if (isset($_GET["categoryid"])) {
    $sql = "SELECT tc.id, sum(tp.views) as viewed, sum(tp.likes) as liked, tp.user_id as trainer_id, category_name, tu.user_name, tp.photo as trainer_photo, tp.experience FROM 
            tbl_category tc INNER join tbl_trainer_profile tp on tp.sport_id = tc.id 
            inner join tbl_user tu on tu.id = tp.user_id
            where tc.id= ".$_GET['categoryid']." group by user_id";
    //echo $sql;
    $rows = $db->query($sql)->fetch();


// $hour1 = 0; $hour2 = 0;
// $date1 = "2018-01-29 20:57:21";
// date_default_timezone_set('Asia/Kolkata');
// $date2 = date('d-m-Y H:i:m');
// $datetimeObj1 = new DateTime($date1);
// $datetimeObj2 = new DateTime($date2);
// $interval = $datetimeObj1->diff($datetimeObj2);
 
// if($interval->format('%a') > 0){
// $hour1 = $interval->format('%a')*24;
// }
// if($interval->format('%h') > 0){
// $hour2 = $interval->format('%h');
// }
 
// echo "Difference between two dates is " . ($hour1 + $hour2) . " hours.";


    if ($db->affected_rows > 0) {
        $arrTmp = array();
        foreach ($rows as $row) {

            $row['last_booking_time'] = rand(1,20)." hour ago";
            
            $arrTmp[] = $row;
        }
        if (!empty($arrTmp)) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['category'] = $arrTmp;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['category'] = 'no record found';
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['gallery'] = 'no record found';
}
//echo json_encode($arrRecord);
echo '<pre>',print_r($arrRecord,1),'</pre>';
?>