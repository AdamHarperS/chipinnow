<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();

if (isset($_GET["trainerid"])) {
    $record_id = strip_tags($_GET["trainerid"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

               
        $sql="UPDATE tbl_trainer_profile SET likes= likes+1 WHERE user_id = ". $record_id;
        $rows = $db->query($sql)->execute();

        $sql = "SELECT tp.user_id as coach_id, sum(tp.likes) as likes, tc.course_name FROM tbl_trainer_profile tp inner join tbl_course tc on tc.trainer_id = tp.user_id WHERE tp.user_id = " . $record_id;
        $rows = $db->query($sql)->fetch();

        if ($db->affected_rows > 0) {
             $arrRecord['data']['success'] = 1;
                $arrRecord['data']['course_likes'] = $rows;
                
            
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['course_likes'] = 'no record found';
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['course_likes'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['course_likes'] = 'no record found';
}

echo json_encode($arrRecord);
?>