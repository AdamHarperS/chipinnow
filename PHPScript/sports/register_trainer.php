<?php

include "../config.inc.php";
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
    $arrRecord['data']['register'] = $arrError;
    echo json_encode($arrRecord);
    exit();
}


//var_dump($_POST);
//
//exit();
//Sanitize post array
$_POST = sanitize($_POST);

//Check User already exist
$sqlUserExist = "SELECT * FROM tbl_user WHERE user_name='" . $db->escape($_POST['name']) . "' and usertype='trainer'";

//$resultUserExist = mysqli_query($con, $sqlUserExist);
$resultUserExist = $db->query($sqlUserExist)->fetch();
if ($db->affected_rows > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['register'] = 'username already exist';
} else {
//Check email already exist
    $sqlEmailExist = "SELECT * FROM tbl_user WHERE email='" . $_POST['email'] . "' and usertype='trainer'";
    $resultEmailExist = $db->query($sqlEmailExist)->fetch();
//$resultEmailExist = mysqli_query($con, $sqlEmailExist);

    if ($db->affected_rows > 0) {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['register'] = 'email already exist';
    } else {

        $sqlInisert = "INSERT INTO tbl_user(user_name, user_password, email, gender,  usertype) "
                . "VALUES ('" . $_POST['name'] . "','" . $_POST['password'] . "','" .
                $_POST['email'] . "','".$_POST['gender']."','trainer')";
        $result = $db->query($sqlInisert)->execute();
        $user_id = $result->_mysqli->insert_id;
        
        $sqlInsert = "INSERT INTO tbl_trainer_profile(user_id, age, level,  sport_id) "
                . "VALUES ('" . $user_id . "','" . $_POST['age'] . "','" .
                $_POST['level'] . "','".$_POST['section']."')";
        $result = $db->query($sqlInsert)->execute();
        if ($result->affected_rows) {
            $sqlSelect = "SELECT  tu.id, user_name, email, user_password, age, level, sport_id "
                    . " FROM tbl_user tu inner join tbl_trainer_profile tp on "
                    . " tp.user_id = tu.id  WHERE email='" . $_POST['email'] . "'"
                    . " and user_password='" . $_POST['password'] . "' and usertype='trainer'";
            //echo $sqlSelect;
            $rowsSelect = $db->query($sqlSelect)->fetch();
            if ($db->affected_rows > 0) {
                foreach ($rowsSelect as $row) {
                    $arrRecord['data']['success'] = 1;
                    $arrRecord['data']['register'] = $row;
                }
            }
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['register'] = "Error in user registration";
        }
    }
}
echo json_encode($arrRecord);
?>