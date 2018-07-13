<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

$sql = "SELECT tc.id, tc.trainer_id, tc.course_name, tc.course_detail, tc.id as course_id, tc.course_price,"
        . " tc.course_location,tc.hours_number as number_of_hours,tc.image as course_image, tca.category_name, "
        . " tu.user_name as coach_name, tu.gender, tc.allowed_trainee, "
        . " tp.experience,tp.level,tc.course_city, tp.sport_id, tp.age  FROM tbl_course tc "
        . " inner join tbl_user tu on tu.id = tc.trainer_id"
        . " inner join tbl_trainer_profile tp on tp.user_id = tu.id"
        . " inner join tbl_category tca on tca.id = tp.sport_id";
//echo $sql;
$where = array();
// if (isset($_GET["filterbyprice"]) && strlen($_GET["filterbyprice"]) > 0) {
//     $where[] = " course_price = " . $_GET["filterbyprice"];
// }
if (isset($_GET["filterbygender"]) && strlen($_GET["filterbygender"]) > 0) {
    $where[] = " gender = '" . $_GET["filterbygender"] . "'";
}
if (isset($_GET["filterbylocation"]) && strlen($_GET["filterbylocation"]) > 0) {
    $where[] = " course_city like '%" . $_GET["filterbylocation"] . "%'";
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
//echo $sql;
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrTmp = array();
    foreach ($rows as $row) {
        $totalSeat = 0;
        $sqlOrder = "SELECT count(*) as total_seat FROM tbl_orders WHERE course_id = " . $row["course_id"] . " and status!='Cancel'";

        $rowsOrder = $db->query($sqlOrder)->fetch();
        if (isset($rowsOrder[0]["total_seat"])) {
            $totalSeat = $rowsOrder[0]["total_seat"];
        }
        


        $row["allowed_trainee"] = $row["allowed_trainee"] - $totalSeat;
        $rating = 0;
        $sqlRating = "SELECT count(rating) as totalRating, IFNULL(SUM(rating),0) as sumRating FROM tbl_rating WHERE coach_id=" . $row["trainer_id"] . " ORDER BY tbl_rating.user_id ASC ";

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

        if (isset($_GET["filterbyrating"]) && strlen($_GET["filterbyrating"]) > 0) {
            if (number_format($row["rating"], 2) == number_format($_GET["filterbyrating"], 2)) {
                $arrTmp[] = $row;
            }
        } else {
            $arrTmp[] = $row;
        }
        //var_dump($arrTmp);
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