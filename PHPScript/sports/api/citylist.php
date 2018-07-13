<?php
include "../config.inc.php";
include "error_response.php";
    mysqli_set_charset( $conn, 'utf8');
    $query = mysqli_query($conn, "SELECT id,city from tbl_trainer_profile group by city order by id asc");

    while ($res = mysqli_fetch_array($query))
    {
        $data[] = array(
            "city" => $res['city']
        );
        $data1=array();
    }
        
        if (isset($data)) {
            if (!empty($data)) {
                $arrRecord['success'] = "1";
                $arrRecord['city'] = $data;
            } else {
                $arrRecord['success'] = "0";
                $arrRecord['city'] = $no_record;
            }
        } else {
            $arrRecord['success'] = "0";
            $arrRecord['city'] = $no_record;
        }
    echo json_encode($arrRecord);

?>