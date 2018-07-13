<?php

include "../config.inc.php";
include "error_response.php";
require_once '../class.database.php';
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
    $arrError[] = $coach_photo;
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['edit_trainee_photo'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {
    $fieldname = 'photo';
    $imgpath = '../images/gallery/';

    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();

    if (!empty($_FILES[$fieldname]['name'])) {

        $file_name = $prefix . "_" . strtolower(basename($_FILES[$fieldname]['name']));
        $file_path = $uploaddir . $file_name;
        if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $file_path)) {
            list($filename, $extension) = explode(".", $file_name);
            $data['image'] = $file_name;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['edit_trainee_photo'] = $image;
            exit();
        }

        $tablename = "tbl_gallery";
        $sqlInisert = "INSERT INTO tbl_gallery(image, trainer_id) VALUES ('" . $file_name . "','" . $_POST['trainer_id'] . "') ";
        $result = $db->query($sqlInisert)->execute();

        if ($result) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['edit_trainee_photo'] = $success;
        } else {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['edit_trainee_photo'] = $error;
        }
    }
}


echo json_encode($arrRecord);
?>