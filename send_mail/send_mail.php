<?php 
	$to = $fullname. "<".$email.">";
	$subject = "=?utf-8?B?".base64_encode("แจ้งรายละเอียดการจองคอร์ส $datacourse[cname] จากไอทีจีเนียส")."?=";
	$message =  "xxx";
									
	$headers = "From:IT Genius Training <contact@itgenius.co.th>\n"; // I suggest you try using only \n 
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/html; charset=utf-8\n";
	$headers .= "Reply-To: IT Genius Training <contact@itgenius.co.th>\n";
	$headers .= "X-Priority: 1\n"; // Urgent message!
	$headers .= "X-MSmail-Priority: High\n";
	$headers .= "X-mailer: itgenius.co.th";
						
	@mail($to, $subject, $message, $headers) or die("Unable to send the Email");
?>