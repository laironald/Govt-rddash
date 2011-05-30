<div class="grid_5">
	<div class="ui-widget-content ui-corner-all sidebar">
          <h1>Contact</h1>
          <ul>
          <li>Your feedback and comments are important to us. We may not be able to get back to each  individual inquiry but plan to develop a frequently asked questions section that will be made available on this web site.
            </li> 
            <li>
            If you wish to contact us directly, please describe the nature of your request in the message box provided, and provide us with a "best methods" way of reaching you  (phone number, noted or different e-mail, address, etc) and we will our best to follow up in a timely manner.    

          </ul>
	</div>
</div>
 
<div class="grid_10 alpha omega">
	<div style="margin-top: 10px; padding:0px 60px 10px 10px;">
	<?php
		require("framework/class.phpmailer.php");

		preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $_POST['email'], $matches);
		if (count($matches) > 0 and $_POST['email']!="") {
			$mail = new PHPMailer(); 
			$mail->IsSMTP(); // send via SMTP
			$mail->SMTPAuth = true; // turn on SMTP authentication
			$mail->Username = "readidatagov@gmail.com"; // SMTP username
			$mail->Password = "r3ad1da7a"; // SMTP password
			$email="readidatagov@gmail.com"; // Recipients email ID
			$name="readi"; // Recipient's name
			$webmaster_email = $_POST['email'];
			$mail->From = $webmaster_email;
			$mail->FromName = $_POST['name'];
			$mail->AddAddress($email,$name);
			$mail->AddReplyTo($webmaster_email,$_POST['name']);
			$mail->WordWrap = 80; // set word wrap
			/*
				$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
				$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
			*/
			//$mail->IsHTML(true);
			$mail->Subject = "[".$_POST['topic']."] ".$_POST['subject'].' by '.$_POST['email'];
			$mail->Body = $_POST['msg'];
			//$mail->AltBody = "This is the body when user views in plain text format"; //Text Body

			if(!$mail->Send()) {
			}
			else {
				$mail = new PHPMailer(); 
				$mail->IsSMTP(); // send via SMTP
				$mail->SMTPAuth = true; // turn on SMTP authentication
				$mail->Username = "readidatagov@gmail.com"; // SMTP username
				$mail->Password = "r3ad1da7a"; // SMTP password
				$email=$_POST['email']; // Recipients email ID
				$name=$_POST['name']; // Recipient's name
				$webmaster_email = "noreply@readidata.nitrd.gov";
				$mail->From = $webmaster_email;
				$mail->FromName = "NoReply";
				$mail->AddAddress($email,$name);
				$mail->AddReplyTo($webmaster_email,"NoReply");
				$mail->WordWrap = 80; // set word wrap
				$mail->Subject = "Thank you for contacting the R&D Dashboard";
				$mail->Body = "Thank you for your comments. Your insights are important and we appreciate your feedback and guidance.\n\nWe won't be able to return a reply to all comments posted but we may return to ask you to further clarify your suggestions.\n\nThank you again.\n\nThe R&D Dashboard Team\n\n-----------------------------------------------------------\n\nYou wrote:\n".$_POST['msg']; //Text Body
				$mail->Send();

				echo "Thank you.  Your message has been sent<hr />";
			}
		} else {
			if ($_POST['email']!="" or count($_POST) > 0)
				echo "Warning: Valid email address required.<hr />";
		}
	?>
	<form class="contact" action="contact.html" method="post">
		<label for="name">Name</label><br/>  <input type="text"  class="place" name="name"  placeholder="Your Name" value="<?=$_POST['name']?>"/><br />
		<label for="email">Email</label><br/> <input type="email" class="place" name="email" placeholder="Your Email Address" value="<?=$_POST['email']?>"/>
		<br /><br />
		<label for="topic">Category</label><br/> 
		<select name="topic" style="margin: 10px 0px;">
			<option value="General">General Feedback</option>
			<option value="Grant">Grant Investment Section</option>
			<option value="Pat">Granted Patent Section</option>
			<option value="App">Applied Patent Section</option>
			<option value="Pub">Publication Section</option>
			<option value="Topic">Topic Modeling</option>
		</select>
		<br /><label for="msg">Message</label><br/> <textarea name="msg" placeholder="We welcome your feedback and ideas on how to improve the site."><?=$_POST['msg']?></textarea>
		<input type="submit" id="sub" value="Send Message" />
	</form>
  </div>
</div>


<script src="js/map_framework_f.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
	google.load("jqueryui", "1.8.4");
	$(document).ready(function() {	
		$("#sub").click(function() {
			$("#sub").val("Sending...");
		});
		<? require_once("js/map.js"); ?>
		$("#sub").button();
	});	
</script>
