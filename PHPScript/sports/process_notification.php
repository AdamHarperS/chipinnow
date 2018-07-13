<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();



if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_notifications";
    $redirect_page = "send_notification.php";
    //Data Fields
    if ($action == "add" || $action == "modify") {
        $date = time();
        $data['content'] = $db->escape($_POST["content"]);
        $data['created_at'] = $date;
        $data['updated_at'] = $date;
    }
//    var_dump($data);
//    exit();
    switch ($action) {
        case 'add' :

        $sendresult = array();

        $querya=mysqli_query($conn,"select * from tbl_firebase_keys");
        $resa=mysqli_fetch_array($querya);
        $google_api_key=$resa['androidkey'];

// Notification Data
        $query=mysqli_query($conn,"select * from tbl_notification_users where device='android'");
        $i=0;
        $reg_id=array();
        while ($res=mysqli_fetch_array($query))
        {

            $reg_id[$i]= $res['reg_id'];
            $i++;
        }
        $time = time();
        $massage = $_POST['content'];

        $registrationIds =  $reg_id ;
        //print_r($registrationIds); exit;
        $payload = array();
        $payload['team'] = 'India';
        $payload['score'] = '5.6';
        $res = array();
        $res['data']['title'] = 'Notification';
        $res['data']['is_background'] = FALSE;
        $res['data']['message'] = $massage;
        $res['data']['image'] = '';
        $res['data']['payload'] =$payload ;
        $res['data']['timestamp'] = date('Y-m-d G:i:s',$time);
        
        $fields = array(
            'registration_ids'  => $registrationIds,
            'data'      => $res
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization: key='.$google_api_key,// . $api_key,
            'Content-Type: application/json'
        );

        $json =  json_encode($fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json);

        $result = curl_exec($ch);

        if ($result === FALSE){
            die('Curl failed: ' . curl_error($ch));
        }   

        curl_close($ch);
        $response=json_decode($result,true);
           // print_r($response); exit();
        if($response['success']>0)
        {

            {
                $sendresult['android'] = $response['success'];
            }
        }
        else
        {
         $sendresult['android'] = 0;
     }


     $queryi=mysqli_query($conn,"select * from tbl_firebase_keys");
     $resi=mysqli_fetch_array($queryi);
     $ios_api_key=$resa['ioskey'];

     $queryios=mysqli_query($conn,"select * from tbl_notification_users where device='ios'");
     $i=0;
     $reg_id=array();
     while ($resios=mysqli_fetch_array($queryios))
     {

        $reg_id[$i]= $resios['reg_id'];
        $i++;
    }
    $registrationIds = $reg_id;


    $msg = array(
        'body'  => $massage,
        'title'     => "Notification",
        'vibrate'   => 1,
        'sound'     => 1,
    );
    
    $fields = array(
        'registration_ids'  => $registrationIds,
        'notification'      => $msg
    );

    $headers = array(
        'Authorization: key=' . $ios_api_key,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );

    if ($result === FALSE){
        die('Curl failed: ' . curl_error($ch));
    }   

    curl_close($ch);
    $response=json_decode($result,true);
            //print_r($response); exit();
    if($response['success']>0)
    {
        {
            $sendresult['ios'] = $response['success'];
        }
    }
    else
    {
     $sendresult['ios'] = 0;
 }
 echo json_encode($sendresult);

 unset($_SESSION["last_data"]);
 if (check_duplicate($tablename, $data, $db) == 0) {
    $result = $db->insert($tablename, $data);
    if ($result) {
        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
    }
} else {
    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
}
break;

case 'modify' :
if (check_duplicate($tablename, $data, $db) == 0) {
    $result = $db->where("noti_id", $_POST["txtid"])->update($tablename, $data);
    if ($result) {
        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
    }
                //unset($_SESSION["last_data"]);
} else {
    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
    $redirect_page = $redirect_page . "?edit=" . $_POST["txtid"];
}
break;

case 'delete' :
deleteMethod($tablename, $db);
exit();
break;

default :
header('location: index.php');
}
header("location:" . $redirect_page);
} else {
    header('location: index.php');
}
?>


