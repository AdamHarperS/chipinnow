<?php
include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

    // if (strlen($_GET["filterbysports"]) == 0) 
    // {
        $sql = "SELECT tc.id, tp.user_id AS coach_id,tc.category_name,tp.level,tp.age, tu.user_name,tu.gender,tp.city, tp.photo AS coach_photo ,tp.experience,tp.city
            FROM tbl_trainer_profile tp
            left JOIN tbl_category tc ON tc.id = tp.sport_id
            left JOIN tbl_user tu ON tu.id = tp.user_id";
            $where = array();
// if (isset($_GET["filterbyprice"]) && strlen($_GET["filterbyprice"]) > 0) {
//     $where[] = " course_price = " . $_GET["filterbyprice"];
// }
if (isset($_GET["filterbygender"]) && strlen($_GET["filterbygender"]) > 0) {
    $where[] = " gender = '" . $_GET["filterbygender"] . "'";
}
if (isset($_GET["filterbylocation"]) && strlen($_GET["filterbylocation"]) > 0) {
    $where[] = " city like '%" . $_GET["filterbylocation"] . "%'";
}
if (isset($_GET["filterbyexperience"]) && strlen($_GET["filterbyexperience"]) > 0) {
    $where[] = " level = '" . $_GET["filterbyexperience"] . "'";
}
if (isset($_GET["filterbysports"]) && strlen($_GET["filterbysports"]) > 0) {
    $where[] = " sport_id = '" . $_GET["filterbysports"] . "'";
}
if (isset($_GET["filterbyage"]) && strlen($_GET["filterbyage"]) > 0) {
    $where[] = " age = '" . $_GET["filterbyage"] . "'";
}



if (count($where) > 0) {
    $sql = $sql . " where " . implode(" and ", $where);
}
    $rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {
        $rating = 0;
        $sqlRating = "SELECT count(rating) as totalRating, IFNULL(SUM(rating),0) as sumRating FROM tbl_rating 
        WHERE coach_id='".$row['coach_id']."' ORDER BY tbl_rating.user_id ASC ";

        $rowsRaing = $db->query($sqlRating)->fetch();
        if ($db->affected_rows > 0) {
            if (!empty($rowsRaing)) {
                $record = $rowsRaing[0];
                if ($record["sumRating"] > 0 && $record["totalRating"] > 0) {
                    $rating = $record["sumRating"] / $record["totalRating"];
                }
            }
        }
        $row['rating'] = $rating;

        if (isset($_GET["filterbyrating"]) && strlen($_GET["filterbyrating"]) > 0) {
            if (number_format($row["rating"], 2) == number_format($_GET["filterbyrating"], 2)) {
                $arrTmp[] = $row;
            }
        } else {
            $arrTmp[] = $row;
        }
    }




    if (!empty($arrTmp)) {
        $arrRecord['data']['success'] = 1;
        $arrRecord['data']['category'] = $arrTmp;
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category'] = $no_record;
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['category'] = $no_record;
}
echo json_encode($arrRecord);
//echo '<pre>',print_r($arrRecord,1),'</pre>';
?>