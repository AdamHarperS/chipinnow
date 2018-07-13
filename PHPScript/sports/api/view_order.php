<?php
include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";

$arrRecord = array();
$arrRecord = array();
$arrError = array();


if (!isset($_GET['coach_id'])) {
    $arrError[] = $coach_id;
} else {
    if (empty($_GET['coach_id'])) {
        $arrError[] = $empty_coach;
    }
}
if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['search_result'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {
        $sql = " SELECT tot.id,tot.user_id,tot.trainer_id,tot.course_id,tot.status,tot.od_date,tot.od_time, tu.user_name as customer_name FROM tbl_orders tot inner join tbl_user"
            . " tu on tu.id = tot.user_id "
            . " inner join tbl_course tc on tc.id = tot.course_id where tot.trainer_id =  "
            . $_GET['coach_id']."";

//    $sql = " SELECT tot.*, tu.user_name as customer_name, tc.course_time FROM tbl_orders tot inner join tbl_user"
//            . " tu on tu.id = tot.user_id "
//            . " inner join tbl_course tc on tc.id = tot.course_id where tot.trainer_id =  "
//            . $_GET['trainer_id']." and tot.status='Under Process'";
    //echo $sql;
    $rows = $db->query($sql)->fetch();

    if ($db->affected_rows > 0) {
        $arrTmp = array();
        foreach ($rows as $row) {


            // $newtimestamp = strtotime($row['order_date'].' + 330 minute');
            


            // $row['order_date'] = date('Y-m-d h:i:s', $newtimestamp);
            $arrTmp[] = $row;
        //}
            if (!empty($arrTmp)) {
                $arrRecord['data']['success'] = 1;
                $arrRecord['data']['view_order'] = $arrTmp;
            } else {
                $arrRecord['data']['success'] = 0;
                $arrRecord['data']['view_order'] = $no_record;
            }
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['view_order'] = $no_record;
    }
}
echo json_encode($arrRecord);
?>