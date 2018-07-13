<?php
include "../config.inc.php";
require_once '../class.database.php';
$arrRecord = array();
if (isset($_GET["categoryid"]))
{
    if (strlen($_GET["categoryid"]) == 0) 
    {
        $sql = "SELECT tc.id, avg( tr.rating ) AS rating, tp.user_id AS coach_id, category_name, tu.user_name,tp.city, tp.photo AS coach_photo, tp.experience
            FROM tbl_category tc
            INNER JOIN tbl_trainer_profile tp ON tp.sport_id = tc.id
            INNER JOIN tbl_user tu ON tu.id = tp.user_id
            LEFT JOIN tbl_rating tr ON tp.user_id = tr.coach_id
            GROUP BY tp.user_id ";
            //echo $sql;
    }
    else
    {
    $sql = "SELECT tc.id, avg(tr.rating) as rating, tp.user_id as coach_id, category_name, tu.user_name,tp.city, tp.photo as coach_photo, tp.experience FROM 
            tbl_category tc INNER join tbl_trainer_profile tp on tp.sport_id = tc.id 
            inner join tbl_user tu on tu.id = tp.user_id
            LEFT join tbl_rating tr on tp.user_id = tr.coach_id
            where tc.id= ".$_GET['categoryid']." group by tp.user_id";
        }
    //echo $sql;
    $rows = $db->query($sql)->fetch();

    if ($db->affected_rows > 0) {
        $arrTmp = array();
        foreach ($rows as $row) {
            //$row['last_booking_time'] = rand(1,5)." hour ago";
            
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
        $arrRecord['data']['success'] = 0;
        $arrRecord['data']['category'] = 'no record found';
    }
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['gallery'] = 'no record found';
}
echo json_encode($arrRecord);
//echo '<pre>',print_r($arrRecord,1),'</pre>';
?>