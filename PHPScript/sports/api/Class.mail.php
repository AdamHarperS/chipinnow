	<?php
	Class Mail{
		private $from = array();
		private $code = null;
		private $to = array();
		private $cc = array();
		private $bcc = array();
		private $subject = null;
		private $message = null;
		private $contentType = "html";
		private $charSet = "UTF-8";


		public function __construct(){
		}

		public function test(){
			return "test";
		}

		public function is_plain(){
			$this->contentType = "plain";
		} 

		public function From($email, $name=null){
			if($name){
				$stFrom = $email."<".$name.">";
			}else{
				$stFrom = $email;
			}

			$this->from = $stFrom;
		}

		public function Subject($subject){
			$this->subject = trim($subject);
		}

		public function Message($body){
			$this->message = $body;
		}

		public function To($email,$name=null){
			$this->toAddress($email, "to", $name); 
		}

		public function Cc($email,$name=null){
			$this->toAddress($email, "cc", $name); 
		}

		public function Bcc($email,$name=null){
			$this->toAddress($email, "bcc", $name); 
		}

		private function toAddress($email, $type, $name=null){
			if($name !== null){
				$stTo = trim($name)."<".trim($email).">";
			}else{
				$stTo = $email;
			}

			$this->{$type} = array($stTo);
		}

		public function Code($code){
			$this->code = $code;
		}

		public function Send(){

			$header = array();
			$errors = '';

			if(count($this->to) === 0 ){
				$error .= "<li>Please Enter Atleast one recipent email address.</li>"; 
			}

			if($this->from === null){
				$errors .= "<li>Please Mention Sender Address</li>";
			}

			if($this->subject === null){
				$errors .= "<li>Subject is Empty</li>";
			}

			if($this->message === null){
				$errors .= "<li>Message is Empty</li>";
			}

			if($this->code === null){
				$errors .= "<li>Unique Code is Empty</li>";
			}

			if($errors !== ''){
				throw new Exception("Email Error : <ul>".$errors."</ul>");
			}

			if(count($this->cc) > 0){
				foreach ($this->cc as $Cc) {
					$header = array("Cc: ".$Cc);
				}
			}

			if(count($this->bcc) > 0){
				foreach ($this->bcc as $Bcc) {
					$header = array("Bcc: ".$Bcc);
				}
			}

			if($this->contentType === "html"){
				$body = '<html><head><title></title><meta http-equiv=Content-Type content=text/html; charset=UTF-8>';
	            $body .= '</head><body><table width="800" border="0"><tr><td><p align="justify" style="color:#000000;">';
	            $body .=  nl2br($this->message); 
	            $body .= '</p></td></tr></table></body></html>';
			}else{
				$body = $this->body;
			}

			$stTo = implode(", ", $this->to);
        	$stHeaders = implode("\r\n", $header);

        	$Send = @mail($stTo, $this->subject, $body, $stHeaders);
	        if(!$Send) {
	            throw new Exception('Email Sending fail.!');
	        }else{
	        	return true;
	        }  
		}

		public function ClearAll(){
			$this->to = array();
			$this->cc = array();
			$this->bcc = array();
		}

		// public function Send($mail,$code){
		// 	$this->email = $mail;
		// 	$this->code = $code;

		// 	// if($this->email != '' && $this->code !=''){
		// 	// 	$to = $this->email;
		// 	// 	$subject = "Account Activation";
		// 	// 	$message = "
		// 	// 			<html>
		// 	// 			<head>
		// 	// 			<title>Sport</title>
		// 	// 			</head>
		// 	// 			<body>
		// 	// 			<p>This id your Unique Code to activate your account </p>
		// 	// 			<table>
		// 	// 			<tr>
		// 	// 			<th>Firstname</th>
		// 	// 			<th>Lastname</th>\
		// 	// 			</tr>
		// 	// 			<tr>
		// 	// 			<td>John</td>
		// 	// 			<td>Doe</td>
		// 	// 			</tr>
		// 	// 			</table>
		// 	// 			</body>
		// 	// 			</html>
		// 	// 			";

		// 	// 	// Always set content-type when sending HTML email
		// 	// 	$headers = "MIME-Version: 1.0" . "\r\n";
		// 	// 	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// 	// 	// More headers
		// 	// 	$headers .= 'From: <webmaster@example.com>' . "\r\n";
		// 	// 	$headers .= 'Cc: myboss@example.com' . "\r\n";

		// 	// 	mail($to,$subject,$message,$headers);

		// 	// 	return true;
		// 	// }else{
		// 	// 	return false;
		// 	// }
		// }
	}

?>