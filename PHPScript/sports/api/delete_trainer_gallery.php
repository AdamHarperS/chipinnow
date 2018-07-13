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
if (empty($_POST['photo'])) {
    $arrError[] = $empty_coach;
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['delete_trainee_photo'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {
    $fieldname = 'image';
    $imgpath = '../images/gallery/';

    $uploaddir = $imgpath;
    $status = 0;
    $prefix = rand();



    $sqlSelect = "SELECT * FROM tbl_gallery WHERE  id in (" . $_POST["photo"] . ")";

    $rows = $db->query($sqlSelect)->fetch();


    if ($db->affected_rows > 0) {
        $rowselect = $rows[0];

        if (!empty($rowselect[$fieldname])) {
            if (file_exists($imgpath . $rowselect[$fieldname])) {
                unlink($imgpath . $rowselect[$fieldname]);
            }
        }
        $sqlDelete = " DELETE FROM tbl_gallery WHERE  id in (" . $_POST["photo"] . ")";
        $resultDelete = $db->query($sqlDelete)->execute();
        if ($resultDelete) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['delete_trainee_photo'] = $photo_delete;
        } else {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['delete_trainee_photo'] = $no_record;
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['delete_trainee_photo'] = $no_record;
    }
}


echo json_encode($arrRecord);
?>