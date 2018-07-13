<?php

require_once("./config.inc.php");
//showValidation();
//echo "<hr>";
//showArray();
//var_dump($_POST);
//exit();

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $action = isset($_POST['process_name']) ? $_POST['process_name'] : '';
    $tablename = "tbl_user";
    $redirect_page = "view_trainer.php";
    //Data Fields
    if ($action == "add" || $action == "modify") {
        $data['user_name'] = $db->escape($_POST["name"]);
        $data['email'] = $db->escape($_POST["email"]);
        $data['user_password'] = $db->escape($_POST["password"]);
        $data['gender'] = $db->escape($_POST["gender"]);
        $data['usertype'] = 'trainer';
        $data['status'] = '1';

        $dataProfile['age'] = $db->escape($_POST["age"]);
        $dataProfile['level'] = $db->escape($_POST["level"]);
        $dataProfile['sport_id'] = $db->escape($_POST["section"]);
        $dataProfile['country'] = $db->escape($_POST["country"]);
        $dataProfile['city'] = $db->escape($_POST["city"]);
        $dataProfile['joining_date'] = $db->escape(date('Y-m-d'));

        $certificate=rand(0,1000000).$_FILES['certificate']['name'];
        $path='images/certificate/';
        move_uploaded_file($_FILES['certificate']['tmp_name'],$path.$certificate);
    }
 //   var_dump($certificate);
 //   exit();
    switch ($action) {

        case 'add' :
            unset($_SESSION["last_data"]);
            if (check_duplicate($tablename, $data, $db) == 0) {
                $result = $db->insert($tablename, $data);
                if ($result) {
                    $tablename = tbl_name("trainer_profile");
                    $dataProfile['user_id'] = $result;
                    $dataProfile['certificate']=$certificate;
                    $result = $db->insert($tablename, $dataProfile);
                    //print_r($data); exit();
                    if ($result) {
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is added successfully", "status" => 'success');
                    } else {
                        $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
                    }
                }
            } else {
                $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => 'Record is already exist', "status" => 'error');
            }
            break;

        case 'modify' :
            if (check_duplicate($tablename, $data, $db) == 0) {
                $result = $db->where("id", $_POST["txtid"])->update($tablename, $data);
                if ($result) {
                    $tablename = tbl_name("trainer_profile");
                    $result = $db->where("user_id", $_POST["txtid"])->update($tablename, $dataProfile);

                    $_SESSION["last_data"] = array("page" => basename(__FILE__), "data" => $data, "message" => "Record is updated successfully", "status" => 'success');
                }
                //unset($_SESSION["last_data"]);
            } else {
                $tablename = tbl_name("trainer_profile");
                $dataProfile['certificate']=$certificate;
                $result = $db->where("user_id", $_POST["txtid"])->update($tablename, $dataProfile);

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


