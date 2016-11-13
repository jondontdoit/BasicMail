<?php
	/************************************
		ACTUALLY SENDS THE EMAIL
	************************************/
	
	//If there's a message to send proceed, otherwise go to inbox
	if (isset($_POST['send']) && $_POST['send'] == 'Send Email') {
		
		require 'config.php';
		require 'header.php';	
		
		//Error checking variables
		$error_message = '';
		$is_error = false;
		
		//If there's no subject provided, set one
		if (!isset($_POST['message-subject']) || trim($_POST['message-subject']) == '')	{
			$new_message_subject = "[No Subject]";
		} else {
			$new_message_subject = stripslashes($_POST['message-subject']);
		}
		
		//Grab the message body and pull out the text before the reply-to part to make sure there's a message
		if (!isset($_POST['message-body']) || $_POST['message-body'] == '') {
			$is_error = true;
		} else {
			$mystring = $_POST['message-body'];
			$findme   = '----- Reply To -----';
			$pos = strpos($mystring, $findme);

			if ($pos !== false && $pos > 0) {
				$mystring = substr($mystring,0,$pos);
			}
			if (trim($mystring) == '') $is_error = true;
		}
		
		//Since there's only one error at the moment, this saves space
		if ($is_error) {
			$error_message = "You need to type something into the Message box.";
		} else {
			$new_message_body = stripslashes($_POST['message-body']);
		}
		
		//If the to address is messed up, send to me with what's going on
		if (!in_array($_POST['to-address'],$to_email)) {
			$new_message_to = $default_email;
			$new_message_body = "Could not find [".$_POST['to-address']."] in list for some reason.\n\n".$new_message_body;
		} else {
			$new_message_to = $_POST['to-address'];
		}	
		
		//If there's nothing wrong up to this point, send the message!
		if (!$is_error) {

			$envelope["from"]= $from_email;
			$envelope["reply_to"]= $from_email;
			$envelope["to"]  = $new_message_to;
			$envelope["subject"]  = $new_message_subject;

			$part1["type"] = TYPEMULTIPART;
			$part1["subtype"] = "mixed";

			$part2["type"] = TYPETEXT;
			$part2["subtype"] = "plain";
			//$part2["description"] = "description3";
			$part2["contents.data"] = $new_message_body."\n\n\n\t";

			$body[1] = $part1;
			$body[2] = $part2;

			//Process and send the email
			$temp_env=unserialize(serialize($envelope));
			$mail=imap_mail_compose($temp_env,$body);
			list($t_header,$t_body)=split("\r\n\r\n",$mail,2);
			$t_header=str_replace("\r",'',$t_header);
			$result=imap_mail($envelope["to"].',',$envelope["subject"],$t_body,$t_header);
			
			//Store the email in the Sent folder in GMail
			imap_append($inbox,$hostname.'Sent',$mail);
		
			// Below is some test code for using SMTP services with the Swift library (not included) - this isn't guaranteed to work
			/*
			require_once 'swift/lib/swift_required.php';
		
			$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
			  ->setUsername($username)
			  ->setPassword($password);

			$mailer = Swift_Mailer::newInstance($transport);
			$message = Swift_Message::newInstance($new_message_subject)
			  ->setFrom(array($username => $from_name))
			  ->setTo($new_message_to)
			  ->setBody($new_message_body);
			$numSent = $mailer->send($message);
			printf("Sent %d messages\n", $numSent);
			*/
			
			echo "<div class=\"message\" style=\"color: green; font-size: 20pt; text-align: center;\">Message Sent Successfully!</div>\n\n";
			echo "<a href=\"index.php\"><div class=\"message-footer\">Back to Inbox</div></a>\n";
		
		} else {
			echo "<div class=\"message\" style=\"color: red; font-size: 20pt; text-align: center;\">".$error_message."</div>\n\n";
			echo "<a href=\"javascript:window.history.back();\"><div class=\"message-footer\">Go back and type a message</div></a>\n";
		}
		
		require 'footer.php';

	} else {
		header("Location: index.php");
	}
			
?> 