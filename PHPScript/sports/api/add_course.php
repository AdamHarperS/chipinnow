<?php

include "../config.inc.php";
include "error_response.php";
require_once '../class.database.php';
require_once 'function.php';
$arrRecord = array();

$arrError = array();
foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $arrError[$key] = $key . " is empty";
    } else if ($key == "email") {

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $arrError[$key] = $key . " is incorrect format";
        }
    }
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['course'] = $arrError;
    echo json_encode($arrRecord);
    exit();
}


//Sanitize post array
$_POST = sanitize($_POST);

//Check User already exist
$sqlUserExist = "SELECT * FROM tbl_course WHERE course_name='" . $_POST['course_name'] . "'";


$resultUserExist = $db->query($sqlUserExist)->fetch();
if ($db->affected_rows > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['course'] = $exists;
} else {
    

    @$image=$_FILES['image']['name'];
    $path='../images/course/';
    move_uploaded_file($_FILES['image']['tmp_name'],$path.@$image);
    
    $sqlInisert = "INSERT INTO tbl_course(trainer_id, course_name, course_detail, course_price,hours_number, course_time, course_location, course_city ,image,course_category, allowed_trainee)"
            . " VALUES ('" . $_POST['trainer_id'] . "','" . $_POST['course_name'] . "','" . $_POST['course_detail'] . "'"
            . ",'" . $_POST['course_price'] . "','".$_POST['hours_number']."','" . $_POST['course_time'] . "','".$_POST['course_location']."','".$_POST['course_city']."','".$image."','" . $_POST['course_category'] . "','" . $_POST['allowed_trainee'] . "')";

    $user_id = $db->query($sqlInisert)->execute();

    if ($user_id->affected_rows) {

        $sqlSelect = "SELECT  * FROM tbl_course WHERE id=" . $user_id->_mysqli->insert_id;
        $rowsSelect = $db->query($sqlSelect)->fetch();
        if ($db->affected_rows > 0) {
            foreach ($rowsSelect as $row) {
                $arrRecord['data']['success'] = 1;
                $arrRecord['data']['course'] = $row;
            }
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['course'] = $error;
    }
}
echo json_encode($arrRecord);
?>