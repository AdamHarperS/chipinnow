<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";


$arrRecord = array();
$arrRecord = array();
$arrError = array();


if (!isset($_GET['coach_id']) && !isset($_GET['user_id'])) {
    $arrError[] = "ID is not provided";
} else {
    if (empty($_GET['coach_id']) && empty($_GET['user_id'])) {
        $arrError[] = "ID is empty";
    }
}
if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['search_result'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {

	if (isset($_GET['coach_id'])) {
		$sql = " SELECT tot.id,tot.user_id,tot.trainer_id as coach_id,tot.course_id,tot.age,tot.weight,tot.height,tot.status,tot.hours,tot.od_date,tot.od_time,tot.location,tot.notes, tu.user_name as customer_name,tc.image as course_photo
        FROM tbl_orders tot 
        inner join tbl_user tu on tu.id = tot.user_id
        inner join tbl_course tc on tc.id = tot.course_id
        where tot.trainer_id =  ". $_GET['coach_id']." and tot.status not in ('Under Process')";
	} else {
		$sql = " SELECT tot.id,tot.user_id,tot.trainer_id as coach_id,tot.course_id,tot.age,tot.weight,tot.height,tot.status,tot.hours,tot.od_date,tot.od_time,tot.location,tot.notes, tu.user_name as customer_name,tc.image as course_photo
        FROM tbl_orders tot 
        inner join tbl_user tu on tu.id = tot.user_id
        inner join tbl_course tc on tc.id = tot.course_id
        where tot.user_id =  ". @$_GET['user_id']." and tot.status not in ('Under Process')";
	}

    

//    $sql = " SELECT tot.*, tu.user_name as customer_name FROM tbl_orders tot inner join tbl_user tu on"
//            . " tu.id = tot.user_id where tot.user_id =  "
//            . $_GET['trainee_id'] . "  and tot.status='Approve'";
   // echo $sql;
    $rows = $db->query($sql)->fetch();

    if ($db->affected_rows > 0) {
        $arrTmp = array();
        foreach ($rows as $row) {

            $sqlTrainer = " SELECT user_name as coach_name FROM  tbl_user where id = " . $row['coach_id'] . "";
            //echo $sql;
            $rowsTrainer = $db->query($sqlTrainer)->fetch();
            //var_dump($rowsTrainer[0]['trainer_name']);
             $row['coach_name'] = @$rowsTrainer[0]['coach_name'];
            //$row['coach_name'] =['coach_name'];
            $arrTmp[] = $row;
        }
        if (!empty($arrTmp)) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['view_status'] = $arrTmp;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['view_status'] = $no_record;
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['view_status'] = $no_record;
    }
}
echo json_encode($arrRecord);
//echo '<pre>',print_r($arrRecord,1),'</pre>';

?>