<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();


if (isset($_GET["filterbyKey"]) && empty($_GET["filterbyKey"])) {
    echo "filterbyKey is emtpy";
    die();
}
if (isset($_GET["filterbyValue"]) && empty($_GET["filterbyValue"])) {
    echo "filterbyValue is emtpy";
    die();
}

// $sql = "SELECT tc.id, tu.gender, tc.course_location, tc.trainer_id, tc.course_name, tc.course_detail, tc.id as course_id, tc.course_price,"
//         . " tc.course_location as course_city, tc.course_country, tc.allowed_trainee,tc.hours_number as number_of_hours, tca.category_name, "
//         . " tu.user_name as trainer_name, tp.photo as trainer_photo  FROM tbl_course tc "
//         . " inner join tbl_user tu on tu.id = tc.trainer_id"
//         . " inner join tbl_trainer_profile tp on tp.user_id = tu.id "
//         . " inner join tbl_category tca on tca.id = tp.sport_id ";

$sql = "SELECT tc.id, tu.gender, tc.course_location, tc.trainer_id, tc.course_name, tc.course_detail, tc.id as course_id, tc.course_price,"
        . " tc.course_location as course_city, tc.allowed_trainee,tc.hours_number as number_of_hours, tca.category_name, "
        . " tu.user_name as coach_name, tp.photo as coach_photo  FROM tbl_course tc "
        . " inner join tbl_user tu on tu.id = tc.trainer_id"
        . " inner join tbl_trainer_profile tp on tp.user_id = tu.id "
        . " inner join tbl_category tca on tca.id = tp.sport_id ";

if (isset($_GET["filterbygender"]) && strlen($_GET["filterbygender"]) > 0) {
    $where[] = " gender = '" . $_GET["filterbygender"] . "'";
}
if (isset($_GET["filterbylocation"]) && strlen($_GET["filterbylocation"]) > 0) {
    $where[] = " course_location like '%" . $_GET["filterbylocation"] . "%'";
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

//echo $sql;
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {
        $rating = 0;
        $sqlRating = "SELECT count(rating) as totalRating, IFNULL(SUM(rating),0) as sumRating FROM tbl_rating WHERE coach_id=" . $row["trainer_id"] . " ORDER BY tbl_rating.user_id ASC ";
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
       
        // $hours = 0;
        // $sqlOrder = "SELECT max(order_date) as last_booking FROM tbl_orders WHERE course_id=" . $row["course_id"];
        // // echo "<br>" . $sqlOrder;
        // $rowsOrder = $db->query($sqlOrder)->fetch();
        // if ($db->affected_rows > 0) {
        //     if (!empty($rowsOrder)) {
        //         $record = $rowsOrder[0];
        //         $date1 = new DateTime($record['last_booking']);
        //         $date2 = new DateTime(date("Y-m-d h:i:s"));
        //         $diff = $date2->diff($date1);
        //         $hours = $diff->h;
        //         $hours = $hours + ($diff->days * 24);
        //     }
        // }

        // $row['last_booking_time'] = $hours . " hour ago";
        $arrTmp[] = $row;
    }
      
        if ($_GET["filterbyrating"] == "rating") {
           

            if (strtolower($_GET['orderby']) == "asc") {
                usort($arrTmp, function($a, $b) {
                    return $a['rating'] - $b['rating'];
                });
            } else if (strtolower($_GET['orderby']) == "desc") {
                usort($arrTmp, function($a, $b) {
                    return $b['rating'] - $a['rating'];
                });
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
?>