<?php

include "../config.inc.php";
include "error_response.php";
require_once '../class.database.php';
$arrRecord = array();

$arrRecord = array();
$arrError = array();

if (!isset($_POST['coach_id'])) {
    $arrError[] = $coach_id;
} else {
    if (empty($_POST['coach_id'])) {
        $arrError[] = $empty_coach;
    }
}
if (!isset($_POST['user_id'])) {
    $arrError[] = $user_id;
} else {
    if (empty($_POST['user_id'])) {
        $arrError[] = $empty_user;
    }
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['add_review'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} else {

    
    $sqlInisert = "INSERT INTO tbl_rating (user_id, coach_id, rating, comments) "
            . " VALUES ('" . $_POST['user_id'] . "','" . $_POST['coach_id'] . "','" . $_POST['rating'] . "','" . $_POST['comments'] . "') ";
    $result = $db->query($sqlInisert)->execute();

    if ($result) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['add_review'] = $success;
    } else {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['add_review'] = $error;
    }
}


echo json_encode($arrRecord);
?>