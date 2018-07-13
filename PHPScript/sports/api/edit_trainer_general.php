<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
require_once 'function.php';
$arrRecord = array();

$arrError = array();

foreach ($_POST as $key => $value) {
    if ($key == 'trainer_id') {
        if (empty($value)) {
            $arrError[$key] = $key . " is empty";
        }
    } 
}
if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['general'] = $arrError;
    echo json_encode($arrRecord);
    exit();
}


//Sanitize post array
$_POST = sanitize($_POST);

//Check User already exist
$sqlUserExist = "SELECT * FROM tbl_trainer_profile WHERE user_id='" . $db->escape($_POST['trainer_id']) . "'";

//$resultUserExist = mysqli_query($con, $sqlUserExist);
$resultUserExist = $db->query($sqlUserExist)->fetch();

if ($db->affected_rows > 0) {

    $sqlUpdate = "UPDATE tbl_trainer_profile SET course_detail='" . $db->escape($_POST['course_detail']) . "',location='" . $db->escape($_POST['location']) . "',"
            . "allow_private='" . $db->escape($_POST['allow_private']) . "',allow_client_location='" 
            . $db->escape($_POST['allow_client_location']) . "',fees_private = '".$db->escape($_POST['fees_private'])."', duration_private='".$db->escape($_POST['duration_private'])."'"
            . " WHERE user_id='" . $db->escape($_POST['trainer_id']) . "'";

    //echo $sqlUpdate;
    
    
    $result = $db->query($sqlUpdate)->execute();
    if ($result->affected_rows) {
        $sqlSelect = "SELECT user_id, course_detail,location, "
                . "allow_private,allow_client_location,fees_private, duration_private from tbl_trainer_profile WHERE user_id='" . $db->escape($_POST['trainer_id']) . "'";
        //echo $sqlSelect;
        $rowsSelect = $db->query($sqlSelect)->fetch();
        if ($db->affected_rows > 0) {
            foreach ($rowsSelect as $row) {
                $arrRecord['data']['success'] = 1;
                $arrRecord['data']['general'] = $row;
            }
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['general'] = $error;
    }
}
echo json_encode($arrRecord);
?>