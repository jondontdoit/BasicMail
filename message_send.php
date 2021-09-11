<?php
	// If the form wasn't submitted to get here, return to the Inbox
	if (!isset($_POST['send']) || $_POST['send'] != 'Send Message') {
		header("Location: index.php");
		die;
	}

	require 'config.php';
	require 'connect.php';

	// Error checking
	$is_error = false;
	$error_message = '';

	// Process Recipient - ensure they're on the allowed list
	if (!isset($_POST['message-to']) || !in_array(strtolower($_POST['message-to']),array_map('strtolower',$allowed_email))) {
		$is_error = true;
		$error_message .= "Could not find '".$_POST['message-to']."' in allowed list.\n";
		$new_message_to = $error_email;
	} else {
		$new_message_to = $_POST['message-to'];
	}
	// Split up "To" field - Format must always be "[Name] <[email]>"
	$pieces = explode('<', $new_message_to);
	if (count($pieces) != 2) {
		$is_error = true;
		$error_message .= "Could not parse recipient address.\n";
	} else {
		$new_message_to_name = trim($pieces[0]);
		$new_message_to_email = trim(str_replace('>','',$pieces[1]));
	}

	// Process Subject - Forgiven if there's none
	if (!isset($_POST['message-subject']) || trim($_POST['message-subject']) == '')	{
		$new_message_subject = $default_subject;
	} else {
		$new_message_subject = stripslashes($_POST['message-subject']);
	}

	// Process Message
	if (!isset($_POST['message-body']) || $_POST['message-body'] == '') {
		$is_error = true;
		$error_message .= "No message was entered.\n";
	} else {
		$new_message_body = stripslashes($_POST['message-body']);
	}

	// No matter what we're going to send an email
	// If there's an error, the message is going to the error address
	if ($is_error) {
		$new_message_subject = $title_page.' Error';
		$new_message_body = $error_message;
	}

	// Send the message!
	// Set this to false if you want to debug and not send any emails
	if (true) {
		require("phpmailer/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP(); // send via SMTP
		//$mail->SMTPDebug = 2;
		$mail->Host = $smtp_host;
		$mail->Port = $smtp_port;
		$mail->SMTPAuth = $smtp_auth;
		$mail->SMTPSecure = "tls";
		$mail->Username = $username; // SMTP username
		$mail->Password = $password; // SMTP password
		$mail->SetFrom($username,$from_name);
		$mail->AddReplyTo($username,$from_name);
		//$mail->FromName = $from_name;
		$mail->AddAddress($new_message_to_email,$new_message_to_name);
		//$mail->WordWrap = 50; // set word wrap
		$mail->Subject = $new_message_subject;
		$mail->IsHTML(true); // send as HTML
		$mail->MsgHTML(str_replace("\n","<br />\n",$new_message_body)); // HTML Body
		$mail->AltBody = $new_message_body; // Plaintext body
		if(!$mail->Send()) {
			$is_error = true;
			$error_message .= $mail->ErrorInfo;
		}
	}

	require 'header.php';
?>
  <div class="message"><?php
  	if ($is_error) {
  		echo '<h1>Error!</h1><span class="message-send-error">'.str_replace("\n","<br />",$error_message).'</span>';
  	} else {
			echo '<span class="message-send-success">Message Sent Successfully!</span>';
  	}
  ?></div>

  <div class="action-button"><?php
  	if ($is_error) {
  		echo '<a href="javascript:window.history.back();"><div>Go Back to Message</div></a>';
  	} else {
  		echo '<a href="index.php"><div>Go to Inbox</div></a>';
  	}
  ?></div>
<?php
	require 'footer.php';
?>