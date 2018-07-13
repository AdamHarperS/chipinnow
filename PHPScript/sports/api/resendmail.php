<?php
	include "../config.inc.php";
	require_once '../class.database.php';
	require "mail.php";

	$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

	if(isset($_POST['email']) && $_POST['email'] != ''){
		$query = "SELECT user_name, email, usertype FROM tbl_user WHERE email='".$_POST['email']."' and status='0'";
		$res = $db->query($query)->fetch();

		if($db->affected_rows > 0){
			if($res[0]['usertype'] === "trainee"){
				$query1 = "SELECT unicode FROM tbl_temp_code WHERE email='".$_POST['email']."'";
				$res1 = $db->query($query1)->fetch();
				if($db->affected_rows > 0){
					$send = Send($res[0]['email'], $res[0]['user_name']);
					if($send){
						$arrRecord['data']['success'] = 0;
	            		$arrRecord['data']['message'] = "mail sent";
					}else{
						$arrRecord['data']['success'] = 0;
			            $arrRecord['data']['message'] = "Mail sending fail";
					}
				}else{
					$arrRecord['data']['success'] = 0;
	           		$arrRecord['data']['message'] = "user alredy activated on this email";
				}
			}else{
				$arrRecord['data']['success'] = 0;
           		$arrRecord['data']['message'] = "no user found";
			}
		}else{
			$arrRecord['data']['success'] = 0;
            $arrRecord['data']['message'] = "no user found";
		}
	}else{
		$arrRecord['data']['success'] = 0;
        $arrRecord['data']['message'] = "no user found";
	}

echo json_encode($arrRecord);
?>