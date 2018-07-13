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


//Sanitize post array
$_POST = sanitize($_POST);

//Check User already exist
$sqlUserExist = "SELECT * FROM tbl_user WHERE user_name='" . $_POST['name'] . "' and usertype='trainee'";

//$resultUserExist = mysqli_query($con, $sqlUserExist);
$resultUserExist = $db->query($sqlUserExist)->fetch();
if ($db->affected_rows > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['register'] = 'username already exist';
} else {
//Check email already exist
    $sqlEmailExist = "SELECT * FROM tbl_user WHERE email='" . $_POST['email'] . "' and usertype='trainee'";
    $resultEmailExist = $db->query($sqlEmailExist)->fetch();
//$resultEmailExist = mysqli_query($con, $sqlEmailExist);

    if ($db->affected_rows > 0) {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['register'] = 'email already exist';
    } else {

        $sqlInisert = "INSERT INTO tbl_user(user_name, user_password,  user_mobile, email, gender, usertype) "
                . "VALUES ('" . $_POST['name'] . "','" . $_POST['password'] . "','" .
                $_POST['mobile'] . "','" . $_POST['email'] . "','" . $_POST['gender'] . "','trainee')";

        $user_id = $db->query($sqlInisert)->execute();

        if ($user_id->affected_rows) {

            $sqlSelect = "SELECT  id, user_fullname, user_name, email, user_password,  usertype, user_mobile FROM tbl_user WHERE email='" . $_POST['email'] . "' and user_password='" . $_POST['password'] . "' and usertype='trainee'";
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