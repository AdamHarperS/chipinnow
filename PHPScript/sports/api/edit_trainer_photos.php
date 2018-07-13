<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

$arrRecord = array();
$arrError = array();

if (!isset($_POST['trainer_id'])) {
    $arrError[] = $coach_id;
} else {
    if (empty($_POST['trainer_id'])) {
        $arrError[] = $empty_coach;
    }
}

if (empty($_FILES['photo']['name'])) {
    $arrError[] = "Coach Photo is not provided";
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['edit_trainee_profile'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {
    $fieldname = 'photo';
    $imgpath = '../images/';

    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();

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
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['edit_trainee_profile'] = $image;
            exit();
        }

        $tablename = "tbl_trainer_profile";

        $result = $db->where("user_id", $_POST["trainer_id"])->update($tablename, $data);
        if ($result) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['edit_trainee_profile'] = $success;
            $arrRecord['data']['image'] = $file_name;
        } else {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['edit_trainee_profile'] = $error;
        }
    }
}


echo json_encode($arrRecord);
?>