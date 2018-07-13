<?php

include "../config.inc.php";
require_once '../class.database.php';

extract($_REQUEST);
$result = array();
if (isset($email) && $email != '') {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = "Please enter valid email";
        $result['data']['success'] = 0;
        $result['data']['message'] = $errors;
    } else {
        $sql = "SELECT email, user_password FROM tbl_user WHERE email='" . strip_tags($email) . "'";
        $rows = $db->query($sql)->fetch();
        if ($db->affected_rows > 0) {
            $row = $rows[0];
            $forgot_pwd = $row['user_password'];
            if (strlen($forgot_pwd) > 0) {
                $to = $row['email'];
                //$to = "mellowsofts@gmail.com";
                $subject = "Forgot Password";
                $message = "<b>your password for sports app is :</b>" . $forgot_pwd;
                $header = "From:testing@sportsproject.com \r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html\r\n";
                $retval = mail($to, $subject, $message, $header);
                if ($retval == true) {
                    $result['data']['success'] = 1;
                    $result['data']['message'] = "password successfully sent. check your email.";
                } else {
                    $result['data']['success'] = 0;
                    $result['data']['message'] = "Message could not be sent...";
                }
            } else {
                $result['data']['success'] = 0;
                $result['data']['message'] = "password not reset";
            }
        } else {
            $errors = "You are not register with us or email id is incorrect";
            $result['data']['success'] = 0;
            $result['data']['message'] = $errors;
        }
    }
} else {
    $result['data']['success'] = 0;
    $result['data']['message'] = "invalid information";
}
echo json_encode($result);
die;
?>