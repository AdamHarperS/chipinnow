<?php

require_once("./config.inc.php");


if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_contact";
    $redirect_page = "aboutus.php";
    
    $imgpath = 'images/';
    $fieldname = "image";
    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();
    
    //Data Fields
    if ($action == "add" || $action == "modify") {        
        $data['contact_us'] = $db->escape($_POST["content"]);
    }

    switch ($action) {
       case 'add' :
       		unset($_SESSION["last_data"]);
            $result = $db->insert($tablename, $data);
            if ($result) {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
            }
            break;
        case 'modify' :
        	$result = $db->where("id", $_POST["txtid"])->update($tablename, $data);
            if ($result) {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
            }
            break;
        case 'delete' :
            deleteMethod($tablename, $db);
            exit();
            break;

        default :
            header('location: contact.php');
    }
    header("location:" . $redirect_page);
} else {
    header('location: contact.php');
}
?>


