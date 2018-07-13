<?php
include "../config.inc.php";
if (isset($_POST['coach_id']) )
{

    $trainer_id = $_POST['coach_id'];
    $category_id = $_POST['category_id'];
    $course_name = $_POST['course_name'];
    $course_address = $_POST['course_location'];
    $course_city = $_POST['course_city'];
    $experience = $_POST['experience'];
    $course_detail = $_POST['course_detail'];
    $course_price = $_POST['course_price'];
    $course_time = $_POST['course_time'];
    $hours_number = $_POST['hours_number'];
    $allowed_trainee = $_POST['allowed_trainee'];

    $image=$_FILES['image']['name'];
    $path='../images/course/';
    move_uploaded_file($_FILES['image']['tmp_name'],$path.$image);

    $sql = "insert into tbl_course values(NULL,'".$trainer_id."','".$category_id."','".$course_name."','".$course_address."','".$course_city."','".$experience."','".mysqli_real_escape_string($conn,$course_detail)."','".$course_price."','".$course_time."','".$hours_number."','".$allowed_trainee."','".$image."')";
    //echo $sql; exit;

    $res = mysqli_query($conn,$sql);

    if($res){
            
            $json[]=array("id"=>"True");
            $jdata['Status'] =  $json;
            echo json_encode($jdata);
    }
    else{
        $ar[]=array("id"=>"False");
        $arr['Status']=$ar;
        echo json_encode($arr);
    }
}
else{
    $ar[]=array("id"=>"False");
    $arr['Status']=$ar;
    echo json_encode($arr);
    //echo '<pre>',print_r($arr,1),'</pre>';
}
?>