<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_gallery";
    $redirect_page = "add_gallery.php?edit=".$db->escape($_POST["txtid"]);
    $imgpath = 'images/gallery/';
    $fieldname = "photo";
    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();
    //Data Fields
    if ($action == "add" || $action == "modify") {

        $data['trainer_id'] = $db->escape($_POST["txtid"]);
        $dataCheck['trainer_id'] = $db->escape($_POST["txtid"]);
        $dataCheck['image'] = $db->escape($_FILES[$fieldname]['name']);
//        $data['location'] = $db->escape($_POST["location"]);
//        $data['allow_private'] = $db->escape($_POST["allow_private"]);
//        $data['allow_client_location'] = $db->escape($_POST["allow_client_location"]);
    }
//    var_dump($data);
//    exit();
    switch ($action) {

        case 'modify' :
            unset($_SESSION["last_data"]);
            if (check_duplicate($tablename, $dataCheck, $db) == 0) {
                
                
                    $fieldname = 'photo';
                    if (!empty($_FILES[$fieldname]['name'])) {

                        $file_name = $prefix . "_" . strtolower(basename($_FILES[$fieldname]['name']));
                        $file_path = $uploaddir . $file_name;
                        if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $file_path)) {
                            list($filename, $extension) = explode(".", $file_name);
                            if (!empty($_POST['oldPhoto'])) {
                                list($filename_old, $extension_old) = explode(".", $_POST['oldPhoto']);
//                                //Delete Old Photo
//                                if (file_exists($uploaddir . $filename_old . "." . $extension_old)) {
//                                    unlink($uploaddir . $filename_old . "." . $extension_old);
//                                }
                            }
                            $data['image'] = $file_name;
                            $status = 1;
                        } else {
                            $status = 101;
                            $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, 'error' => 'Image Uploading Error');
                        }
                    }

                    $result = $db->insert($tablename, $data);
                    if ($result) {
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
                    } else {
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
                    }
                
            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
            }
            break;

        case 'modify1' :
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
                    $data['photo'] = $file_name;
                    $status = 1;
                } else {
                    $status = 101;
                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, 'error' => 'Image Uploading Error');
                }
            }

            $result = $db->where("user_id", $_POST["txtid"])->update($tablename, $data);
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


