<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

$arrRecord = array();
$arrError = array();


//exit();
if (!isset($_POST['trainer_id'])) {
    $arrError[] = $coach_id;
} else {
    if (empty($_POST['trainer_id'])) {
        $arrError[] = $empty_coach;
    }
}
if (!isset($_POST['user_id'])) {
    $arrError[] = $user_id;
} else {
    if (empty($_POST['user_id'])) {
        $arrError[] = $empty_user;
    }
}
if (!isset($_POST['course_id'])) {
    $arrError[] = "Course ID is not provided";
} else {
    if (empty($_POST['course_id'])) {
        $arrError[] = "Course ID is empty";
    }
}


if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['request_order'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {

    

    
    $sqlInisert = "INSERT INTO tbl_orders(user_id, trainer_id, course_id, age, weight, height, hours, od_date, od_time, location, notes) "
            . " VALUES ('" . $_POST['user_id'] . "','" . $_POST['trainer_id'] . "','" . $_POST['course_id'] 
            . "', '" . $_POST['age'] . "', '" . $_POST['weight'] . "','" . $_POST['height'] . "','" . $_POST['hours']
            . "','" . $_POST['date'] . "','" . $_POST['time'] . "','" . $_POST['location'] . "','" . $_POST['notes'] . "') ";
    
    $result = $db->query($sqlInisert)->execute();
    if ($result) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['request_order'] = 'Coach request has been sent';
    } else {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['request_order'] = $error;
    }
    
    $order_id = $result->_mysqli->insert_id;


    $sqlkey = "select * from tbl_firebase_keys";
    $rowskey = $db->query($sqlkey)->fetch();

    $fireBaseKeyIOS = $rowskey[0]['ioskey'];
    $fireBaseKeyAndroid = $rowskey[0]['androidkey'];

        $sql = "select * from tbl_orders where id =" . $order_id;
        $user_id = $db->query($sql)->fetch();
        $coach = $user_id[0]['trainer_id'];
        $user = $user_id[0]['user_id'];

        $sqlAdmin = "select * from  tbl_user where usertype = 'admin'";
        $resAdmin = $db->query($sqlAdmin)->fetch();
        $admin_id = '';
        if ($resAdmin) {
            $admin_id = $resAdmin[0]['id'];
        }
        $sqlusers = "select * from  tbl_notification_users where user_id in( " . $coach . ")";
        if (strlen($admin_id) > 0) {
            $sqlusers = "select * from  tbl_notification_users where user_id in( " . $coach . "," . $admin_id . ")";
        }
        $res = $db->query($sqlusers)->fetch();
        $regId = @$res[0]['reg_id'];
        $time = time();

        $check = mysqli_query($conn,"select user_name from  tbl_user where id = '".$user."'");
        $check_type = mysqli_fetch_array($check);

        $name = $check_type['user_name'];

        $content = 'Trainee '.$name.' send you coaching request';
        

        if (@$res[0]['device'] == 'Android' || @$res[0]['device'] == 'android') {

            $payload = array();
            $payload['team'] = 'India';
            $payload['score'] = '5.6';
            $res = array();
            $res['data']['title'] = 'Order Status';
            $res['data']['is_background'] = FALSE;
            $res['data']['message'] = $content;
            $res['data']['image'] = '';
            $res['data']['payload'] = $payload;
            $res['data']['timestamp'] = date('Y-m-d G:i:s', $time);

            $fields = array(
                'to' => $regId,
                'data' => $res,
            );

            $url = 'https://fcm.googleapis.com/fcm/send';
            $headers = array(
                'Authorization: key=' . $fireBaseKeyAndroid,
                'Content-Type: application/json'
            );
            $json = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            $response = json_decode($result, true);

            //      if($response['success']==1){    
            // $arrRecord['data']['success'] = 1;
            // $arrRecord['data']['response_order'] = 'Order Status is update successfully';
            //      }else{
            //          $arrRecord['data']['success'] = 1;
            // $arrRecord['data']['response_order'] = 'Order Status is not updated a';
            //      }
        } elseif (@$res[0]['device'] == 'iphone' || @$res[0]['device'] == 'Iphone' || @$res[0]['device'] == 'IOS' || @$res[0]['device'] == 'ios' || @$res[0]['device'] == 'Ios') {
            $token = $regId;
            //$title = "Carbon";
            $body = $content;
            //print_r($body); exit;
            $notification = array('title' => 'Order Status', 'text' => $body, 'image' => 'no Image');
            $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high');
            $json = json_encode($arrayToSend);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=' . $fireBaseKeyIOS;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response, true);
            //print_r($response); exit;
            //      if($response['success']==1){
            // $arrRecord['data']['success'] = 1;
            // $arrRecord['data']['response_order'] = 'Order Status is update successfully';
            //      }else{
            //          $arrRecord['data']['success'] = 1;
            // $arrRecord['data']['response_order'] = 'Order Status is not updated i';
            //      }
}
}
echo json_encode($arrRecord);
?>