<?php
include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";

$arrRecord = array();
if (isset($_REQUEST["device_id"]) && isset($_REQUEST["device"]) && isset($_REQUEST['reg_id']) && $_REQUEST["device_id"] !='' && $_REQUEST["device"] !='' && $_REQUEST['reg_id'] !='') {
    $device_id = $_REQUEST["device_id"];
    $reg_id = $_REQUEST["reg_id"];
    $device = $_REQUEST["device"];

    if($device_id  && $device && $reg_id) {
        $device_chk = "SELECT (u_id) FROM tbl_notification_users where device_id = '".$_REQUEST["device_id"]."'";
        $div = $db->query($device_chk)->fetch();

        if(isset($div[0]['u_id']) && $div[0]['u_id'] != ''){
            $sql1 = "UPDATE tbl_notification_users SET device_id = '".$_REQUEST["device_id"]."',user_id='".$_REQUEST["user_id"]."',reg_id = '".$_REQUEST["reg_id"]."', device = '".$_REQUEST["device"]."' WHERE u_id = ".$div[0]['u_id'];
            $rows = $db->query($sql1)->execute();

            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['message'] = $device;
        }else{
            $sql = "INSERT INTO tbl_notification_users VALUES(null,'".$_REQUEST["user_id"]."','".$_REQUEST["device_id"]."','".$_REQUEST["reg_id"]."','".$_REQUEST["device"]."','1')";
            $rows = $db->query($sql)->execute();

            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['message'] = $device;
        }  

    }else{
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['message'] = $not_register;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['message'] = $not_register;
}



echo json_encode($arrRecord);
?>