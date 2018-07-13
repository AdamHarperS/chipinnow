<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

if (isset($_GET["trainerid"])) {
    $record_id = strip_tags($_GET["trainerid"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

        $arrTmp= array();
        $sql = "SELECT id, trainer_id, image FROM tbl_gallery WHERE trainer_id = " . $record_id;
        $rows = $db->query($sql)->fetch();
        foreach ($rows as $row) {
            $arrTmp[] = $row;
        }

        if (!empty($arrTmp)) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['gallery'] = $arrTmp;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['gallery'] = $no_record;
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['gallery'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['gallery'] = $no_record;
}

echo json_encode($arrRecord);
?>