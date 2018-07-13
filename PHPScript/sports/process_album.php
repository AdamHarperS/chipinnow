<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_album";
    $redirect_page = "view_album.php";
    $status = 0;
    $prefix = rand();
    
    //Data Fields
    if ($action == "add" || $action == "modify") {
        $data['category_name'] = $db->escape($_POST["name"]);
        $dataCheck['category_name'] = $db->escape($_POST["name"]);
    }
//    var_dump($data);
//    exit();
    switch ($action) {

       case 'add' :
            unset($_SESSION["last_data"]);
            if (check_duplicate($tablename, $dataCheck, $db) == 0) {
            	
	            $result = $db->insert($tablename, $data);
	            if ($result) {
	                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
	            } else {
			        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Image Uploading Error', "status" => 'error');
			    }

            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
            }
            break;

        case 'modify' :

            if (check_duplicate($tablename, $dataCheck, $db) == 0) {
               // echo $uploaddir . $_POST['oldPhoto'];
                //Photo
                $fieldname = 'photo';
                if (!empty($_FILES[$fieldname]['name'])) {

                    $file_name = $prefix . "_" . strtolower(basename($_FILES[$fieldname]['name']));
                    $file_path = $uploaddir . $file_name;
                    if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $file_path)) {
                        list($filename, $extension) = explode(".", $file_name);
                       if (!empty($_POST['oldPhoto'])) {
                        list($filename_old, $extension_old) = explode(".", $_POST['oldPhoto']);
                        //Delete Old Photo
                        if (file_exists($uploaddir . $filename_old . "." . $extension_old)) {
                            unlink($uploaddir . $filename_old . "." . $extension_old);
                        }
                    }
                        $data['image'] = $file_name;
                        $status = 1;
                    } else {
                        $status = 101;
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, 'error' => 'Image Uploading Error');
                    }
                }
                $result = $db->where("id", $_POST["txtid"])->update($tablename, $data);
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
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


