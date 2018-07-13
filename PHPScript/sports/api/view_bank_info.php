<?php

include "../config.inc.php";
require_once '../class.database.php';
include "error_response.php";
$arrRecord = array();

$sql = "SELECT id, account_name, account_number, bank, iban, swift_code, routing_code, description FROM tbl_bank";
$rows = $db->query($sql)->fetch();

if ($db->affected_rows > 0) {
    $arrRecord['data']['success'] = 1;
    $arrRecord['data']['bank_info'] = $rows;
} else {
    $arrRecord['data']['success'] = 0;
    $arrRecord['data']['bank_info'] = $no_record;
}
echo json_encode($arrRecord);
?>