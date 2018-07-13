<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_orders";
    $redirect_page = "response_order.php";


    //Data Fields
    if ($action == "add" || $action == "modify") {

        $data['trainer_id'] = $db->escape($_POST["trainer_id"]);
        $data['user_id'] = $db->escape($_POST["trainee_id"]);
        $data['height'] = $db->escape($_POST["height"]);
        $data['age'] = $db->escape($_POST["age"]);
        $data['weight'] = $db->escape($_POST["weight"]);
        $data['course_id'] = $db->escape($_POST["course_id"]);
        $dataCheck['trainer_id'] = $db->escape($_POST["trainer_id"]);
        $dataCheck['user_id'] = $db->escape($_POST["trainee_id"]);
        $dataCheck['height'] = $db->escape($_POST["height"]);
        $dataCheck['age'] = $db->escape($_POST["age"]);
        $dataCheck['weight'] = $db->escape($_POST["weight"]);
        $dataCheck['course_id'] = $db->escape($_POST["course_id"]);
    }
//    var_dump($data);
//    exit();
    switch ($action) {

        case 'add' :
            unset($_SESSION["last_data"]);
            if (check_duplicate($tablename, $dataCheck, $db) == 0) {

                
                $result = $db->insert($tablename, $data);
                
                
                if ($result) {
                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
                }
            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
            }
            break;

        case 'modify' :

            if (check_duplicate($tablename, $dataCheck, $db) == 0) {
               
                $result = $db->where("id", $_POST["txtid"])->update($tablename, $data);
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
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


