<?php

// require_once '../class.database.php';
include "../config.inc.php";
$arrRecord = array();

$arrRecord = array();
$arrError = array();

if (!isset($_POST['order_id'])) {
    $arrError[] = "Order ID is not provided";
} else {
    if (empty($_POST['order_id'])) {
        $arrError[] = "Order ID is empty";
    }
}
if (!isset($_POST['status'])) {
    $arrError[] = "Status is not provided";
} else {
    if (empty($_POST['status'])) {
        $arrError[] = "Status is empty";
    }
}
//if (!isset($_POST['user_id'])) {
//    $arrError[] = "User ID is not provided";
//} else {
//    if (empty($_POST['user_id'])) {
//        $arrError[] = "User ID is empty";
//    }
//}



if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['response_order'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {


    // echo "SELECT tor.id,tor.user_id,tu.id,tu.usertype from tbl_orders tor inner join tbl_user tu on tor.user_id=tu.id where tor.id='".$_POST['order_id']."'"; exit;
    $check = mysqli_query($conn,"SELECT tor.id,tor.user_id,tu.id,tu.usertype from tbl_orders tor inner join tbl_user tu on tor.user_id=tu.id where tor.id='".$_POST['order_id']."'");
    $check_type = mysqli_fetch_array($check);

    //$trainee = $check_type;
    //print_r($trainee); exit();
    if ($check_type['usertype'] === 'trainee') {

        $sqlInisert = "UPDATE tbl_orders SET status='" . $_POST['status'] . "' where id = " . $_POST['order_id']; 
        $result = $db->query($sqlInisert)->execute();

        if ($result) {

        $sql = "select user_id from tbl_orders where id =" . $_POST['order_id'];
        $user_id = $db->query($sql)->fetch();

        if ($user_id) {
            $user = $user_id[0]['user_id'];

            if ($_POST['status'] == "Completed") {
                $content = "Your Order is Completed.";
            }
            else if ($_POST['status'] == "Cancelled") {
                $content = "Your Order is Cancelled.";
            }
            else {
                $content = "Your Order is Under Process.";
            }

            $sqlkey = "select * from tbl_firebase_keys";
            $rowskey = $db->query($sqlkey)->fetch();

            $sqlAdmin = "select * from  tbl_user where usertype = 'admin'";
            $resAdmin = $db->query($sqlAdmin)->fetch();
            $admin_id = '';
            if ($resAdmin) {
                $admin_id = $resAdmin[0]['id'];
            }
            $sqlusers = "select * from  tbl_notification_users where user_id in( " . $user . ")";
            if (strlen($admin_id) > 0) {
                $sqlusers = "select * from  tbl_notification_users where user_id in( " . $user . "," . $admin_id . ")";
            }
            $res = $db->query($sqlusers)->fetch();

            $fireBaseKeyIOS = $rowskey[0]['ioskey'];
            $fireBaseKeyAndroid = $rowskey[0]['androidkey'];

            if ($res) {
                $regId = $res[0]['reg_id'];
                $time = time();
                if ($res[0]['notification'] == 1) {
                    if ($res[0]['device'] == 'Android' || $res[0]['device'] == 'android') {

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
                    } elseif ($res[0]['device'] == 'iphone' || $res[0]['device'] == 'Iphone' || $res[0]['device'] == 'IOS' || $res[0]['device'] == 'ios' || $res[0]['device'] == 'Ios') {
                        $token = $regId;
                        //$title = "Carbon";
                        $body = $content;
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

                        //      if($response['success']==1){
                        // $arrRecord['data']['success'] = 1;
                        // $arrRecord['data']['response_order'] = 'Order Status is update successfully';
                        //      }else{
                        //          $arrRecord['data']['success'] = 1;
                        // $arrRecord['data']['response_order'] = 'Order Status is not updated i';
                        //      }
                    }
                }

                $arrRecord['data']['success'] = 1;
                $arrRecord['data']['response_order'] = $_POST['status'];
                $arrRecord['data']['response_order'] = 'Order Status is update successfully';
            } else {
                $arrRecord['data']['success'] = 0;
                $arrRecord['data']['response_order'] = 'Not Updated';
            }
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['response_order'] = 'Not Updated';
    }

    } else {

        $sqlInisert = "UPDATE tbl_orders SET status='" . $_POST['status'] . "' where id = " . $_POST['order_id']; 
        $result = $db->query($sqlInisert)->execute();

        if ($result) {

        $sql = "select user_id from tbl_orders where id =" . $_POST['order_id'];
        $user_id = $db->query($sql)->fetch();

        if ($user_id) {
            $user = $user_id[0]['user_id'];

            if ($_POST['status'] == "Accepted") {
                $content = "Your Order is Accepted";
            }
            else if ($_POST['status'] == "Rejected") {
                $content = "Your Order is Rejected.";
            }
            else if ($_POST['status'] == "Completed") {
                $content = "Your Order is Completed.";
            }
            else if ($_POST['status'] == "Cancelled") {
                $content = "Your Order is Cancelled.";
            }
            else {
                $content = "Your Order is Under Process.";
            }

            $sqlkey = "select * from tbl_firebase_keys";
            $rowskey = $db->query($sqlkey)->fetch();

            $sqlAdmin = "select * from  tbl_user where usertype = 'admin'";
            $resAdmin = $db->query($sqlAdmin)->fetch();
            $admin_id = '';
            if ($resAdmin) {
                $admin_id = $resAdmin[0]['id'];
            }
            $sqlusers = "select * from  tbl_notification_users where user_id in( " . $user . ")";
            if (strlen($admin_id) > 0) {
                $sqlusers = "select * from  tbl_notification_users where user_id in( " . $user . "," . $admin_id . ")";
            }
            $res = $db->query($sqlusers)->fetch();

            $fireBaseKeyIOS = $rowskey[0]['ioskey'];
            $fireBaseKeyAndroid = $rowskey[0]['androidkey'];

            if ($res) {
                $regId = $res[0]['reg_id'];
                $time = time();
                if ($res[0]['notification'] == 1) {
                    if ($res[0]['device'] == 'Android' || $res[0]['device'] == 'android') {

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
                    } elseif ($res[0]['device'] == 'iphone' || $res[0]['device'] == 'Iphone' || $res[0]['device'] == 'IOS' || $res[0]['device'] == 'ios' || $res[0]['device'] == 'Ios') {
                        $token = $regId;
                        //$title = "Carbon";
                        $body = $content;
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

                        //      if($response['success']==1){
                        // $arrRecord['data']['success'] = 1;
                        // $arrRecord['data']['response_order'] = 'Order Status is update successfully';
                        //      }else{
                        //          $arrRecord['data']['success'] = 1;
                        // $arrRecord['data']['response_order'] = 'Order Status is not updated i';
                        //      }
                    }
                }

                $arrRecord['data']['success'] = 1;
                $arrRecord['data']['response_order'] = $_POST['status'];
//                $arrRecord['data']['response_order'] = 'Order Status is update successfully';
            } else {
                $arrRecord['data']['success'] = 0;
                $arrRecord['data']['response_order'] = 'Not Updated';
            }
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['response_order'] = 'Not Updated';
    }
        echo "This is Trainer";
    }
exit();
    
    
 
    

    
}

echo json_encode($arrRecord);
?>