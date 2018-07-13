<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

if (isset($_GET["trainerid"])) {
    $record_id = strip_tags($_GET["trainerid"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

        $sql = "SELECT user_id, video FROM tbl_trainer_profile WHERE user_id = " . $record_id;
        $rows = $db->query($sql)->fetch();

        if ($db->affected_rows > 0) {
             $arrRecord['data']['success'] = 1;
                $arrRecord['data']['video_profile'] = $rows;
                
            
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['video_profile'] = $no_record;
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['video_profile'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['video_profile'] = $no_record;
}

echo json_encode($arrRecord);
?>