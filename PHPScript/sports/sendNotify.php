<?php

require_once 'config.inc.php';

//var_dump($_POST);
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

//$sql = "select * from " . tbl_name("notifications"). " where noti_id =13";
//$notification_content = $db->query($sql)->fetch();

$sqlkey = "select * from " . tbl_name("firebase_keys");
$rowskey = $db->query($sqlkey)->fetch();

//$sqlusers = "select * from " . tbl_name("notification_users")." where user_id = 5791";
$sqlusers = "select * from " . tbl_name("notification_users")." where user_id in (". $_POST["trainee_id"].")";
//echo $sqlusers;
$user = $db->query($sqlusers)->fetch();


$fireBaseKeyIOS = $rowskey[0]['ioskey'];
$fireBaseKeyAndroid = $rowskey[0]['androidkey'];

$content = $_POST["notification_text"];

if($user)
{
    foreach ($user as $res)
    {

        $regId=$res['reg_id'];
        // $user_id = $res['user_id'];
        $time = time();
        if($res['notification'] == 1)
        {
            if($res['device']=='Android' || $res['device']=='android')
            {
                $payload = array();
                $payload['team'] = 'India';
                $payload['score'] = '5.6';
                $res = array();
                $res['data']['title'] = 'Notification';
                $res['data']['is_background'] = FALSE;
                $res['data']['message'] = $content;
                $res['data']['image'] = '';
                $res['data']['payload'] =$payload ;
                $res['data']['timestamp'] = date('Y-m-d G:i:s',$time);

                $fields = array(
                    'to' => $regId,
                    'data' => $res,
                );

                $url = 'https://fcm.googleapis.com/fcm/send';
                $headers = array(
                    'Authorization: key='. $fireBaseKeyAndroid,
                    'Content-Type: application/json'
                );
                $json =  json_encode($fields);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$json);

                $result = curl_exec($ch);
                //var_dump($result);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                curl_close($ch);
                $response=json_decode($result,true);
                if($response['success']==1)
                {  
                    // echo "True Android <br/>";
                    // print_r($response);   
                   // echo true;
                }
                else
                {
                    // echo "False Adnroid <br/>";
                    // print_r($response);   
                   // echo false;
                }
            }
            elseif($res['device']=='iphone' || $res['device']=='Iphone' || $res['device']=='IOS' || $res['device']=='ios' || $res['device']=='Ios')
            {
                $token = $regId;
                //$title = "Carbon";
                $body = $content;
                $notification = array('title' =>'Notification' , 'text' => $body,'image' => 'no Image');
                $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
                $json = json_encode($arrayToSend);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='.$fireBaseKeyIOS;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                          
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

                $response = curl_exec($ch);
                //var_dump($result);
                curl_close($ch);
                $response=json_decode($response,true);
                if($response['success']==1)
                {
                    // print_r($response);    
                    // echo "True Ios <br />";
                   // echo true;
                }
                else
                {
                    // print_r($response);   
                    // echo "False Ios <br/>";
                    //echo false;
                }
            }
        }
    }
    echo "Notifaiction Sent";
}
else
{
    echo "Notifaiction Not Sent";
}

?>