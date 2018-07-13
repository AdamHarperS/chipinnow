<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();
$arrError = array();
if (!isset($_GET['category_id'])) {
    $arrError[] = "Category ID is not provided";
} else {
    if (strlen($_GET['category_id'])==0) {
        $arrError[] = "Category ID is empty";
    }
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category_views'] = $arrError;
    echo json_encode($arrRecord);
    exit();
} 
if (isset($_GET["category_id"])) {
    $record_id = strip_tags($_GET["category_id"]);
    if (preg_match('/^[0-9]*$/', $record_id)) {

        $sql="UPDATE tbl_category SET viewed= viewed+1 WHERE id = ". $record_id;
        $rows = $db->query($sql)->execute();
        
        $sql = "SELECT id, category_name, viewed FROM tbl_category WHERE id = " . $record_id;
        $rows = $db->query($sql)->fetch();

        if ($db->affected_rows > 0) {
             $arrRecord['data']['success'] = 1;
                $arrRecord['data']['category_views'] = $rows;
                
            
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['category_views'] = 'no record found';
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category_views'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category_views'] = 'no record found';
}

echo json_encode($arrRecord);
?>