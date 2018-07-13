<?php
require_once("./config.inc.php");

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d H:i:s');

    $sqli = mysqli_query($conn,"insert into tbl_complaint_reply set username= '".$username."', email = '".$email."', message = '".$message."',
    date = '".$date."'");

    $to      = @$email;// Send email to our user
    $subject = 'Complaint Update'; // Give the email a subject 
    $mailmessage = $message;// Our message above including the link
                             
    $headers = 'From:admin@sports.com' . "\r\n"; // Set from headers
    mail($to, $subject, $mailmessage, $headers);
    header('Location: complaint.php');
    
}
?>