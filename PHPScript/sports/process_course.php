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
    $course_detail = mysqli_real_escape_string($conn, $_REQUEST['course_detail']);
    $get_sport_id = mysqli_query($conn,"select sport_id from tbl_trainer_profile where user_id='".$_REQUEST['trainer_id']."'");
    $sport_id = mysqli_fetch_array($get_sport_id);

    $category_id = $sport_id['sport_id'];
    //Data Fields
    if ($action == "add") {

        $image=rand(0,1000000).$_FILES['image']['name'];
        $path='images/course/';
        move_uploaded_file($_FILES['image']['tmp_name'],$path.$image);
        mysqli_set_charset( $conn, 'utf8');
        $addtour="insert into tbl_course
        (`id`, `trainer_id`, `course_name`, `course_detail`, `course_price`, `course_time`, `hours_number`, `course_location`, `course_city`, `allowed_trainee`, `image`, `course_category`)

        values(NULL,'".$_REQUEST['trainer_id']."','".$_REQUEST['course_name']."','".$course_detail."','".$_REQUEST['course_price']."','".$_REQUEST['course_time']."','".$_REQUEST['number_of_hours']."','".$_REQUEST['course_location']."','".$_REQUEST['course_city']."','".$_REQUEST['total_trainee_allowed']."','".$image."','".$category_id."')";

        $rows = $db->query($addtour)->execute();
        echo "<script>alert('Record Successfully Inserted');
        window.location='add_course.php';</script>";
        
//        $data['course_time'] = date("H:i", strtotime(str_replace(" ","",$data['course_time'])));
    }
    if ($action == "modify") {
        $image=$_FILES['image']['name'];
        
        if($image == '')
        {

            $updatecourse="UPDATE tbl_course SET `trainer_id`='".$_REQUEST['trainer_id']."',`course_name`='".$_REQUEST['course_name']."',`course_detail`='".$course_detail."',`course_price`='".$_REQUEST['course_price']."',`course_time`='".$_REQUEST['course_time']."',`hours_number`='".$_REQUEST['number_of_hours']."',`course_location`='".$_REQUEST['course_location']."',`course_city`='".$_REQUEST['course_city']."',`allowed_trainee`='".$_REQUEST['total_trainee_allowed']."',`course_category`='".$category_id."' WHERE `id` ='".$_POST['txtid']."'";
            $rows = $db->query($updatecourse)->execute();
            echo "<script>window.location='view_course.php';</script>";
            
        }
        else
        {           
            $path="images/course/";
            move_uploaded_file($_FILES['image']['tmp_name'],$path.$image);

            $updatecourse="UPDATE tbl_course SET `trainer_id`='".$_REQUEST['trainer_id']."',`course_name`='".$_REQUEST['course_name']."',`course_detail`='".$course_detail."',`course_price`='".$_REQUEST['course_price']."',`course_time`='".$_REQUEST['course_time']."',`hours_number`='".$_REQUEST['number_of_hours']."',`course_location`='".$_REQUEST['course_location']."',`course_city`='".$_REQUEST['course_city']."',`allowed_trainee`='".$_REQUEST['total_trainee_allowed']."',`image`='".$image."',`course_category`='".$category_id."' WHERE `id` ='".$_POST['txtid']."'";
            $rows = $db->query($updatecourse)->execute(); 
            echo "<script>window.location='view_course.php';</script>";        
        }
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