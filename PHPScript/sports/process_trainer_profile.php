<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_trainer_profile";
    $redirect_page = "view_trainer.php";
    $imgpath = 'images/';
    $fieldname = "image";
    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();
    //Data Fields
    if ($action == "add" || $action == "modify") {
        $data['experience'] = $db->escape($_POST["experience"]);
        $data['college'] = $db->escape($_POST["college"]);
        $data['qualification'] = $db->escape($_POST["qualification"]);
        $data['achievement'] = $db->escape($_POST["achivement"]);
        $data['license'] = $db->escape($_POST["license"]);
        
        $data['about_coach'] = $db->escape($_POST["about_coach"]);
        // $data['duration_private'] = $db->escape($_POST["private_lesson_duration"]);
    }
//    var_dump($data);
//    exit();
    switch ($action) {

        case 'add' :
            unset($_SESSION["last_data"]);
            if (check_duplicate($tablename, $data, $db) == 0) {
                $result = $db->insert($tablename, $data);
                if ($result) {
                    $tablename = tbl_name("trainer_profile");
                    $dataProfile['user_id'] = $result;
                    $result = $db->insert($tablename, $dataProfile);
                    if ($result) {
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
                    } else {
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
                    }
                }
            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
            }
            break;

        case 'modify' :
            //echo $uploaddir . $_POST['oldPhoto'];
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
            //Video
            $uploaddir = $uploaddir . "videos/";
            //video
            $fieldname = 'video';
            if (!empty($_FILES[$fieldname]['name'])) {

                $file_name = $prefix . "_" . strtolower(basename($_FILES[$fieldname]['name']));
                $file_path = 'videos/' . $file_name;
                if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $file_path)) {
                    list($filename, $extension) = explode(".", $file_name);
                    if (!empty($_POST['oldVideo'])) {
                        list($filename_old, $extension_old) = explode(".", $_POST['oldVideo']);
                        //Delete Old Photo
                        if (file_exists($uploaddir . $filename_old . "." . $extension_old)) {
                            unlink($uploaddir . $filename_old . "." . $extension_old);
                        }
                    }
                    $data['video'] = $file_name;

                    

                    $status = 1;
                } else {
                    $status = 101;
                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, 'error' => 'Image Uploading Error');
                }
            }
            $result = $db->where("user_id", $_POST["txtid"])->update($tablename, $data);
//            if (check_duplicate($tablename, $data, $db) == 0) {
//                
//                if ($result) {
//                    $tablename = tbl_name("trainer_profile");
//                    $result = $db->where("user_id", $_POST["txtid"])->update($tablename, $data);
//
//                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
//                }
//                //unset($_SESSION["last_data"]);
//            } else {
//               
//
//                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
//                $redirect_page = $redirect_page . "?edit=" . $_POST["txtid"];
//            }
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


