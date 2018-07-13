<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();
$arrError = array();

if (!isset($_GET['user_id'])) {
    $arrError[] = "Trainee/Coach ID is not provided";
} else {
    if (empty($_GET['category_id'])) {
        $arrError[] = "Trainee/Coach ID is empty";
    }
}



$sql = "SELECT tc.id, tc.category_name,  tc.image, tc.viewed, tc.liked FROM tbl_category tc";




//$sql = "SELECT tc.id, tc.category_name,  tc.image, sum(tp.views) as viewed, sum(tp.likes) as liked FROM tbl_category tc"
//        . " inner join tbl_trainer_profile tp on tp.sport_id = tc.id "
//        . " WHERE tp.user_id= " . $_GET['user_id']
//        . "  group by tp.sport_id";
//$sql = "SELECT tc.id, tc.category_name,  tc.image, sum(tp.views) as viewed, sum(tp.likes) as liked FROM tbl_category tc"
//        . " inner join tbl_trainer_profile tp on tp.sport_id = tc.id "
//        . " WHERE tp.user_id= " . $_GET['user_id']
//        . " and  tp.sport_id = " . $_GET['category_id'] . " group by tp.sport_id";
//
//$sql = "SELECT tc.id, tc.category_name, tf.isfollow, tc.image, sum(tp.views) as viewed, sum(tp.likes) as liked FROM tbl_category tc"
//        . " inner join tbl_trainer_profile tp on tp.sport_id = tc.id "
//        . " right join tbl_follow tf on tp.user_id = tf.user_id WHERE tp.user_id= " . $_GET['user_id'] 
//        . " and  tf.category_id = " . $_GET['category_id']." group by tp.sport_id";


$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {

        if (isset($_GET["user_id"])) {
            if (strlen($_GET["user_id"]) > 0) {
                $sqlFollow = "SELECT * from tbl_follow where user_id= " . $_GET['user_id'] . " and  category_id = " . $row['id'];
                $rowsFollow = $db->query($sqlFollow)->fetch();
                if ($db->affected_rows > 0) {
                    $row["isfollow"] = 1;
                } else {
                    $row["isfollow"] = 0;
                }
            }
            else
            {
                 $row["isfollow"] = 0;
            }
        } else {
            $row["isfollow"] = 0;
        }
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

//    $sql = "SELECT tc.id, tc.category_name,  tc.image, sum(tp.views) as viewed, sum(tp.likes) as liked FROM tbl_category tc"
//        . " inner join tbl_trainer_profile tp on tp.sport_id = tc.id "
//        . " WHERE tp.user_id= " . $_GET['user_id']
//        . " and  tp.sport_id = " . $_GET['category_id'] . " group by tp.sport_id";
//    
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category'] = 'no record found';
}

echo json_encode($arrRecord);
?>