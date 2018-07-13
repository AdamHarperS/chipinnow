<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_course";
    $redirect_page = "view_course.php";
    //Data Fields
    if ($action == "add" || $action == "modify") {
        $data['trainer_id'] = $db->escape($_POST["trainer_id"]);
        $data['course_name'] = $db->escape($_POST["course_name"]);
        $data['course_detail'] = $db->escape($_POST["course_detail"]);
        $data['course_price'] = $db->escape($_POST["course_price"]);
        $data['course_time'] = $db->escape($_POST["course_time"]);
        $data['course_time'] = $data['course_time'];
        $data['hours_number'] = $db->escape($_POST["number_of_hours"]);
        $data['course_location'] = $db->escape($_POST["course_location"]);
//        $data['course_time'] = date("H:i", strtotime(str_replace(" ","",$data['course_time'])));
    }

//    echo date("H:i", strtotime(str_replace(" ","",$data['course_time'])));
//    echo date("H:i", strtotime("04:25PM"));
//    var_dump($data['course_time']);
//    exit();
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


