<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();
$arrError = array();


if (!isset($_GET['category_id'])) {
    $arrError[] = "Category ID is not provided";
} else {
    if (empty($_GET['category_id'])) {
        $arrError[] = "Category ID is empty";
    }
}
if (!isset($_GET['user_id'])) {
    $arrError[] = "Trainee/Coach ID is not provided";
} else {
    if (empty($_GET['category_id'])) {
        $arrError[] = "Category ID is empty";
    }
}
if (!isset($_GET['isfollow'])) {
    $arrError[] = "isfollow is not provided";
} else {
    if (strlen($_GET['isfollow']) == 0) {
        $arrError[] = "isfollow is empty";
    }
}

if (count($arrError) > 0) {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['register'] = $arrError;
    echo json_encode($arrRecord);
    exit();
}

if ($_GET['isfollow'] == 1) {
    $sqlUpdate = "INSERT INTO tbl_follow(user_id, category_id, isfollow) VALUES (" . $_GET['user_id'] . "," . $_GET['category_id']
            . "," . $_GET['isfollow'] . ")";
    $rows = $db->query($sqlUpdate)->execute();
} else if ($_GET['isfollow'] == 0) {
    $sqlUpdate = "DELETE FROM tbl_follow WHERE user_id= " . $_GET['user_id'] . " and  category_id = " . $_GET['category_id']
            . " and isfollow=1";

    $rows = $db->query($sqlUpdate)->execute();
}

$sql = "SELECT * from tbl_follow where user_id= " . $_GET['user_id'] . " and  category_id = " . $_GET['category_id'];
            
//$sql = "SELECT tc.id, tc.category_name, isfollow, tc.image, ifnull(sum(tp.views),0) as viewed, ifnull(sum(tp.likes),0) as liked FROM tbl_category tc"
//        . " left outer join tbl_trainer_profile tp on   tc.id = tp.sport_id group by tc.id";

$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {
        $arrTmp[] = $row;
    }
    if (!empty($arrTmp)) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['category'] = $arrTmp;
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category'] = 'no record found';
    }
} else {
    $arrTmp[] = array("id"=>0,"user_id"=>$_GET["user_id"],"category_id"=>$_GET["category_id"],"isfollow"=>0);
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category'] = $arrTmp;
//    $arrRecord['data']['category'] = 'no record found';
}

echo json_encode($arrRecord);
?>