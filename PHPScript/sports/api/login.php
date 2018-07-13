<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

if (isset($_REQUEST["email"]) && isset($_REQUEST["userpass"])) {
    $user_name = preg_match('/^[0-9a-z\W@.]*$/', strip_tags($_REQUEST["email"]));
    $user_pass = preg_match('/^[0-9a-z\W]*$/', strip_tags($_REQUEST["userpass"]));

    if ($user_name && $user_pass) {

        $sql = "SELECT id, user_name, email, user_password,  usertype, user_mobile, gender FROM tbl_user WHERE email='" . strip_tags($_REQUEST["email"]) . "' and user_password='" . strip_tags($_REQUEST["userpass"]) . "' and status = 1";
        // $sql = "SELECT id, user_name, email, user_password,  usertype, user_mobile, gender, status FROM tbl_user WHERE email= 'redixbit.praveenkumar@gmail.com'";
        $rows = $db->query($sql)->fetch();

        if ($db->affected_rows > 0) {
            foreach ($rows as $row) {
                $arrRecord['data']['success'] = 1;
                $arrRecord['data']['login'] = $row;
            }
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['login'] = $no_record;
        }      

    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['login'] = $no_record;
    }
   
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['login'] = $no_record;
}

echo json_encode($arrRecord);
 ?>