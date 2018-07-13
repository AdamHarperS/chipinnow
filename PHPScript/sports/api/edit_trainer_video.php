<?php
//ini_set('post_max_size', '164M');
//ini_set('upload_max_filesize', '164M');

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

$arrRecord = array();
$arrError = array();

//var_dump($_FILES);
//var_dump($_POST);

if (!isset($_POST['coach_id'])) {
    $arrError[] = $coach_id;
} else {
    if (empty($_POST['coach_id'])) {
        $arrError[] = $coach_id;
    }
}

if (empty($_FILES['video']['name'])) {
    $arrError[] = "Coach Video is not provided";
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['edit_trainee_video'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {
    $fieldname = 'video';
    $imgpath = '../videos/';

    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();

    if (!empty($_FILES[$fieldname]['name'])) {

        $file_name = $prefix . "_" . strtolower(basename($_FILES[$fieldname]['name']));
        $file_path = $uploaddir . $file_name;
        if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $file_path)) {
            list($filename, $extension) = explode(".", $file_name);
            if (!empty($_POST['oldVideo'])) {
                list($filename_old, $extension_old) = explode(".", $_POST['oldVideo']);
                //Delete Old Video
                if (file_exists($uploaddir . $filename_old . "." . $extension_old)) {
                    unlink($uploaddir . $filename_old . "." . $extension_old);
                }
            }
            $data['video'] = $file_name;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['edit_trainee_video'] = $image;
            exit();
        }

        $tablename = "tbl_trainer_profile";

        $result = $db->where("user_id", $_POST["coach_id"])->update($tablename, $data);

            $sqlVideo = " SELECT video FROM  tbl_trainer_profile where user_id = '".$_POST["coach_id"]."'";
            $rowsTrainer = $db->query($sqlVideo)->fetch();
            $row['video'] = @$rowsTrainer[0]['video'];

        if ($result) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['video_profile'] = $success;
            $arrRecord['data']['video'] = $row;
        } else {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['video_profile'] = $error;
        }
    }
}
echo json_encode($arrRecord);
?>