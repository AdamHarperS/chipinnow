<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php"
require_once 'function.php';
$arrRecord = array();

$arrError = array();
foreach ($_POST as $key => $value) {
    if ($key == 'trainer_id') {
        if (empty($value)) {
            $arrError[$key] = $key . " is empty";
        }
    } else if ($key == "email") {

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $arrError[$key] = $key . " is incorrect format";
        }
    }
}
if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['profile'] = $arrError;
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

    $sqlUpdate = "UPDATE tbl_trainer_profile SET city='".$db->escape($_POST['city'])."',
    about_coach='".$db->escape($_POST['about_coach'])."',experience='" . $db->escape($_POST['experience']) . "',
    college='" . $db->escape($_POST['college']) . "',qualification='" . $db->escape($_POST['qualification']) . "',
    achievement='" . $db->escape($_POST['achievement']) . "',license='" . $db->escape($_POST['license']) . "' WHERE user_id='" . $db->escape($_POST['trainer_id']) . "'";

    $result = $db->query($sqlUpdate)->execute();
    if ($result->affected_rows) {

    $sql = "SELECT * FROM tbl_trainer_profile WHERE user_id = '" . $db->escape($_POST['trainer_id']) . "'";
        $rows = $db->query($sql)->fetch();

        $sqlOrder = "SELECT( SELECT count(*) FROM tbl_orders WHERE trainer_id= '" . $db->escape($_POST['trainer_id']) . "' and status='Accepted') as accepted,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $db->escape($_POST['trainer_id']) . " and status='Cancelled') as canceled,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $db->escape($_POST['trainer_id']) . " and status='Pending') as pending,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $db->escape($_POST['trainer_id']) . " and status='Rejected') as disapprove,"
                . " ( SELECT count(*) FROM tbl_orders WHERE trainer_id=" . $db->escape($_POST['trainer_id']) . " and status='Completed') as complete";
        $rowsOrder = $db->query($sqlOrder)->fetch();
        if ($db->affected_rows > 0) {

            if (!empty($rowsOrder)) {
                $rows[0]['accepted'] = $rowsOrder[0]['accepted'];
                $rows[0]['canceled'] = $rowsOrder[0]['canceled'];
                $rows[0]['pending'] = $rowsOrder[0]['pending'];
                $rows[0]['disapprove'] = $rowsOrder[0]['disapprove'];
                $rows[0]['complete'] = $rowsOrder[0]['complete'];
            }
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['general'] = $rows;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['general'] = $error;
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['profile'] = $error;
    }
}
echo json_encode($arrRecord);
?>