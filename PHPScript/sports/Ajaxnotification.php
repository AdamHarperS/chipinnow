<?php
$querystring=$_REQUEST['id'];
require_once 'config.inc.php';

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$sql = "select * from " . tbl_name("notifications"). " where noti_id =".$querystring;
$notification_content = $db->query($sql)->fetch();

$sqlkey = "select * from " . tbl_name("firebase_keys");
$rowskey = $db->query($sqlkey)->fetch();

$sqlusers = "select * from " . tbl_name("notification_users");
$user = $db->query($sqlusers)->fetch();


$fireBaseKeyIOS = $rowskey[0]['ioskey'];
$fireBaseKeyAndroid = $rowskey[0]['androidkey'];

$content = $notification_content[0]['content'];

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

                if ($result === FALSE){
                        die('Curl failed: ' . curl_error($ch));
                    }   

                    curl_close($ch);
                    $response=json_decode($result,true);
                    //print_r($response); exit();
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
            }
            elseif($res['device']=='iphone' || $res['device']=='Iphone' || $res['device']=='IOS' || $res['device']=='ios' || $res['device']=='Ios')
            {
                $token = $regId;
                //$title = "Carbon";
                $body = $content;
                $notification = array('title' =>'Notification' , 'text' => $body);
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
            }
        }
    }
    echo json_encode($sendresult);
}
else
{
    echo "false";
}

?>