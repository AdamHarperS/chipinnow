<?php
	include "config.inc.php";
	require_once 'class.database.php';
		
	$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	
	if($_GET['Ag63tCsk'] !=''){
		$sqlInisert1 = "SELECT * FROM tbl_temp_code WHERE email = '".$_GET['email']."'";
	    $user_id = $db->query($sqlInisert1)->fetch();

	    if($db->affected_rows > 0){
	    	if($user_id[0]['time_stamp'] <= $user_id[0]['time_stamp'] + 48*60*60){
		    	if($user_id[0]['email'] === $_GET['email'] && $user_id[0]['unicode'] === urldecode($_GET['Ag63tCsk'])){
		    		$data['status'] = 1;
		    		$res = $db->where("email", $_GET["email"])->update("tbl_user", $data);
		    		if($res){
		    			$sqlDelete = " DELETE FROM tbl_temp_code WHERE  email = '".$_GET['email']."'";
	        			$resultDelete = $db->query($sqlDelete)->execute();
	        			if($resultDelete){
	        				alert_done();
	        			}
		    		}else{
		    			alert();
		    		}
		    	}else{	
		    		alert();
		    	}
	    	}
	    }else{
	    	alert();
	    }
	}else{
		redirect("/");
	}

	function alert_done(){
		echo "<script>alert('Congratularion your account activated');window.close();</script>";
	}

	function alert(){
		echo "<script>alert('Activation Like Exprire');window.close();</script>";
	}

?>