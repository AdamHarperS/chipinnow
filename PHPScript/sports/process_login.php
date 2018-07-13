<?php

require_once("./config.inc.php");


if (!isset($_SESSION["user"])) {
    //header("location:".$baseurl."index.php");	
}
//var_dump($_POST);
//
//
//foreach ($_POST as $key => $value) {
//    echo "<br>\$data['" . $key . "'] = \$_POST[\"" . $key . "\"];";
//}
//

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $redirect_page = "dashboard.php";
    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_user";
    //$redirect_page = "add_unit.php";
    $sql = "select * from " . $tablename . " where email='" . $db->escape($_POST["user_name"]) . "' and user_password='" . $db->escape($_POST["user_pwd"]) . "'";
    $rows = $db->query($sql)->fetch();
    if ($db->affected_rows > 0) {
        $rows = $rows[0];
        if (!empty($rows["id"])) {

            $_SESSION['user_id'] =  $rows["id"];
            $_SESSION['user_name'] =  $rows["user_name"];
            $_SESSION['user_role'] =  $rows["usertype"];
            $_SESSION['user_status'] =  $rows["user_status"];
            $_SESSION['unit_id'] =  $rows["unit_id"];
            header("location:dashboard.php");
        }

        //exit();
        header("location:" . $redirect_page);
    }
    else {
    header('location: index.php?status=fail');
}
} else {
    header('location: index.php?status=fail');
}
?>

