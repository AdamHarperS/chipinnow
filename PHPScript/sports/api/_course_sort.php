<?php

include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();

if (isset($_GET["sortby"]) && empty($_GET["sortby"])) {
    echo "Sortby is emtpy";
    die();
}
if (isset($_GET["orderby"]) && empty($_GET["orderby"])) {
    echo "Orderby is emtpy";
    die();
}


if (isset($_GET["sortby"])) {
    $sql = "SELECT * from tbl_course ";
    if ($_GET["sortby"] == "price") {
        //$sql = "SELECT * from tbl_course order by course_price " . $_GET["orderby"];
        $sql = "SELECT tc.id, tc.trainer_id, tc.course_name, tc.course_detail, tc.id as course_id, tc.course_price, tca.category_name, "
        . " tu.user_name as coach_name, tp.photo as coach_photo FROM tbl_course tc "
        . " inner join tbl_user tu on tu.id = tc.trainer_id"
        . " inner join tbl_trainer_profile tp on tp.user_id = tu.id "
        . " inner join tbl_category tca on tca.id = tp.sport_id order by tc.course_price " . $_GET["orderby"];
        
    }
    if ($_GET["sortby"] == "rating") {
        $sql = "SELECT * from tbl_course ";
    }
    $rows = $db->query($sql)->fetch();

    if ($db->affected_rows > 0) {
        $arrTmp = array();
        foreach ($rows as $row) {

            if ($_GET["sortby"] == "rating") {
                $rating = 0;
                $sqlRating = "SELECT count(rating) as totalRating, IFNULL(SUM(rating),0) as sumRating FROM tbl_rating WHERE coach_id=" . $row["trainer_id"] . " ORDER BY totalRating " . $_GET['orderby'];
                //   echo $sqlRating;
                //echo "<br>" . $sqlRating;
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
            }

            $arrTmp[] = $row;

            if (strtolower($_GET['orderby']) == "asc") {
                usort($arrTmp, function($a, $b) {
                    return $a['rating'] - $b['rating'];
                });
            }
            else if (strtolower($_GET['orderby']) == "desc") {
                usort($arrTmp, function($a, $b) {
                    return $b['rating'] - $a['rating'];
                });
            }
        }
        if (!empty($arrTmp)) {
            $arrRecord['data']['success'] = 1;
            $arrRecord['data']['sorting'] = $arrTmp;
        } else {
            $arrRecord['data']['success'] = 0;
            $arrRecord['data']['sorting'] = 'no record found';
        }
    } else {
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['sorting'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['gallery'] = 'no record found';
}
echo json_encode($arrRecord);
?>