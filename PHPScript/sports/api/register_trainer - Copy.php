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


//var_dump($_FILES);
//
//exit();
//Sanitize post array
$_POST = sanitize($_POST);

//Check User already exist
$sqlUserExist = "SELECT * FROM tbl_user WHERE user_name='" . $_POST['user_name'] . "' and usertype='trainee'";

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

        $sqlInisert = "INSERT INTO tbl_user(user_name, user_password,  user_mobile, email,  usertype) "
                . "VALUES ('" . $_POST['user_fullname'] . "','" . $_POST['user_name'] . "','" . $_POST['password'] . "','" .
                $_POST['phone'] . "','" . $_POST['email'] . "','trainee')";

        if (mysqli_query($con, $sqlInsert)) {


            $sql = mysqli_query($con, "SELECT  id, user_fullname, user_name, email, user_password,  usertype, user_mobile FROM tbl_user WHERE user_name='" . $_POST['user_name'] . "' and user_password='" . $_POST['password'] . "' and usertype='trainee'");
            if (mysqli_num_rows($sql) > 0) {
                while ($row = mysqli_fetch_assoc($sql)) {
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