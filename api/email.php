<?php
	require("../framework/class.phpmailer.php");
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); // send via SMTP
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "readidatagov@gmail.com"; // SMTP username
	$mail->Password = "r3ad1da7a"; // SMTP password
	$webmaster_email = "donotrespond@readidata.nitrd.gov"; //Reply to this email ID
	$email="email@email.com"; // Recipients email ID
	$name="name"; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "do not respond";
	$mail->AddAddress($email,$name);
	$mail->AddReplyTo($webmaster_email,"Webmaster");
	$mail->WordWrap = 50; // set word wrap
	/*
		$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
		$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	*/
	$mail->IsHTML(true);
	$mail->Subject = "This is the subject";
	$mail->Body = "Hi,
	This is the HTML BODY "; //HTML Body
	$mail->AltBody = "This is the body when user views in plain text format"; //Text Body
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	else {
		echo "Message has been sent";
	}
?>
