<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_bank";
    $redirect_page = "view_bank_info.php";
    //Data Fields
    if ($action == "add" || $action == "modify") {
        $data['account_name'] = $db->escape($_POST["account_name"]);
        $data['account_number'] = $db->escape($_POST["account_number"]);
        $data['bank'] = $db->escape($_POST["bank"]);
        $data['iban'] = $db->escape($_POST["iban"]);
        $data['swift_code'] = $db->escape($_POST["swift_code"]);
        $data['routing_code'] = $db->escape($_POST["routing_code"]);
        $data['description'] = $db->escape($_POST["description"]);
    }

    switch ($action) {

        case 'add' :
            unset($_SESSION["last_data"]);
            if (check_duplicate($tablename, $data, $db) == 0) {
                $result = $db->insert($tablename, $data);
                if ($result) {
                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
                }
            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
            }
            break;

        case 'modify' :
            if (check_duplicate($tablename, $data, $db) == 0) {
                $result = $db->where("id", $_POST["txtid"])->update($tablename, $data);
                if ($result) {
                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
                }
                //unset($_SESSION["last_data"]);
            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
                $redirect_page = $redirect_page . "?edit=" . $_POST["txtid"];
            }
            break;

        case 'delete' :
            deleteMethod($tablename, $db);
            exit();
            break;

        default :
            header('location: index.php');
    }
    header("location:" . $redirect_page);
} else {
    header('location: index.php');
}
?>