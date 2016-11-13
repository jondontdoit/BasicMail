<?php
	/************************************
		COMPOSE/REPLY TO AN EMAIL
	************************************/

	// Check if it's a reply or not, and focus on the appropriate text box
	if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
		$is_reply = true;
		$onload = " onLoad=\"document.getElementById('message-body').focus();\"";
	} else {
		$is_reply = false;
		$onload = " onLoad=\"document.getElementById('message-subject').focus();\"";
	}

	require 'config.php';
	require 'header.php';
	
	$message_subject = ''; //Default new email subject
	
	if ($is_reply) {
		//Load and process message
		$overview = imap_fetch_overview($inbox,$_GET['uid'],FT_UID);
		$message = imap_fetchbody($inbox,$_GET['uid'],2,FT_UID);
		if ($message == '') $message = imap_fetchbody($inbox,$_GET['uid'],1,FT_UID);
		$message = str_replace(array('<br/>','<br>'),"\n",$message);
		$message = strip_tags(htmlspecialchars_decode($message));
		$message = str_replace(array("=\r\n",' =A0'),'',$message);
		$message_subject = (stristr($overview[0]->subject,'Re: ') === FALSE ? 'Re: ' : '').htmlspecialchars($overview[0]->subject);
	}
?>
<form name="compose" id="compose" action="sendmail.php" method="POST">

<div class="message-header">
	<table border="0">
		<tr>
			<td><b>To:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php
				if ($is_reply) {
					echo '<input type="hidden" name="to-address" value="'.$overview[0]->from.'" />'.htmlspecialchars($overview[0]->from);
				} else {
					//Loop through all the to-email addresses
					echo '<select name="to-address" class="to-address">'."\n";
					foreach($to_email as $to_data) {
						echo "<option value=\"".$to_data."\">&nbsp;&nbsp;&nbsp;".strip_tags($to_data)."&nbsp;&nbsp;&nbsp;</option>\n";
					}
					echo '</select>';
				}
			?></td>
		</tr>
		<tr>
			<td><b>Subject:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php
				if ($is_reply) {
					echo '<input type="hidden" name="message-subject" id="message-subject" value="'.$message_subject.'" class="message-subject" />'.$message_subject;
				} else {
					echo '<input type="text" name="message-subject" id="message-subject" value="'.$message_subject.'" class="message-subject" />';
				}			
			?></td>
		</tr>
	</table>
</div>

<div class="message">
	<textarea name="message-body" id="message-body" class="message-body"><?php
		if ($is_reply) echo "\n\n\n\n----- Reply To -----\nDate: ".htmlspecialchars($overview[0]->date)."\nSubject: ".htmlspecialchars($overview[0]->subject)."\n\n".$message."\n\n";
	?></textarea>
</div>

<input type="hidden" name="send" value="Send Email" />
<a href="javascript:document.forms['compose'].submit();"><div class="message-footer">Send E-mail</div></a>

</form>
<?php
	require 'footer.php';	
?> 