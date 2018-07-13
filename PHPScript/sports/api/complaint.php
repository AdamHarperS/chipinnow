<?php
include "../config.inc.php";
if (isset($_POST['email']) )
{
    $email=$_POST['email'];
    $description=$_POST['description'];

    date_default_timezone_set("Asia/Kolkata");
    $date = date('Y-m-d H:i:s');

    $sql = "insert into tbl_complaint values(NULL,'".$email."','".$description."','".$date."')";
    $res = mysqli_query($conn,$sql);
    if($res){
            
            $json[]=array("id"=>"Success");
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