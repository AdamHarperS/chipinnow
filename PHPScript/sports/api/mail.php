<?php

require '../PHPMailer/PHPMailerAutoload.php';

function Send($email, $name) {
    $string = randstring();
    $user = InserTempCode($email, $string);

    if ($user) {
        $url = $_SERVER['SERVER_NAME'] . "/UserValidation.php?Ag63tCsk=" . urlencode($string) . "&email=" . $email;
        $res = Sendmail($email, $string, $name, $url);
        if ($res) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function InserTempCode($email, $string) {
    $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

    $chkQuery = "SELECT * FROM tbl_temp_code WHERE email = '" . $email . "'";
    $chk = $db->query($chkQuery)->fetch();

    if ($db->affected_rows > 0) {
        $sqlInisert1 = "UPDATE tbl_temp_code SET unicode = '" . $string . "',time_stamp = '" . time() . "' WHERE email = '" . $email . "'";
        $res = $db->query($sqlInisert1)->execute();
    } else {
        $sqlInisert1 = "INSERT INTO tbl_temp_code(email, unicode, time_stamp) VALUES ('" . $email . "','" . $string . "'," . time() . ")";
        $res = $db->query($sqlInisert1)->execute();
    }

    if ($res) {
        return true;
    } else {
        return false;
    }
}

function randstring() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#^*_=';
    $randstring = '';
    for ($i = 0; $i < 64; $i++) {
        $randstring .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randstring;
}

function Sendmail($email, $uniqcode, $name, $url) {
    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = HOST;            // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = MAILUSER;                 // SMTP username
    $mail->Password = MAILUSERPWD;                           // SMTP password
    $mail->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
    $mail->Port = MAILPORT;                                    // TCP port to connect to
    $mail->setFrom(FROMMAIL, 'Activate account');
    $mail->addAddress($email, $name);     // Add a recipient, Name is optional
    // $mail->addReplyTo('freak7vi@sports.freaktemplate.com', 'Information');
    // $mail->addCC('');
    // $mail->addBCC('');
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Member account activation';
    $mail->Body = '<div style="background-color:#fff;padding:20px">
							<div style="max-width:600px;margin:0 auto">
								<div style="padding:10px 10px;line-height:1.5em;color:#737373">
								    <p style="color:#737373">Hi ' . $name . ',</p>
								        Please follow the link to activate your account within 48 hours.<br><br>
								    
								    <h4>Click To below link to Activate Account</h4>
								    <br>
								    http://' . $url . '


								    <br><br>
								    <p style="border-bottom:1px solid #fff;padding-bottom:20px;margin-bottom:20px;color:#737373">
								       <a href="#">support team</a> is available to answer any questions.
								    </p>
								</div> 
									<p>DO NOT reply this email!</p>
							</div>
						</div>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        return true;
    }
}

?>