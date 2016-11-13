<?php
	/************************************
		VIEW EMAILS
	************************************/

	// Have to provide a message id to view this page
	if (!isset($_GET['uid']) || (isset($_GET['uid']) && !is_numeric($_GET['uid']))) {
		header("Location: index.php");
		die;
	}

	require 'config.php';	
	require 'header.php';	

	// If we're viewing a sent message, switch to the sent mailbox
	if (isset($_GET['mbox']) && $_GET['mbox'] == 'sent') {
		imap_reopen($inbox,$hostname.'Sent');
	}

	// grab email and remove strange HTML formatting as well as mailto links
	$overview = imap_fetch_overview($inbox,$_GET['uid'],FT_UID);
	$message = imap_fetchbody($inbox,$_GET['uid'],2,FT_UID);
	if ($message == '') $message = nl2br(htmlspecialchars(imap_fetchbody($inbox,$_GET['uid'],1,FT_UID)));
	$message = str_replace('=3D','=',$message);
	$message = str_replace('="mailto:','="index.php" title="',$message);
	$message = str_replace(array("=\r\n",' =A0'),'',$message);
?>

<div class="message-header">
	<table border="0">
		<tr>
			<td><b>From:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo htmlspecialchars($overview[0]->from); ?></td>
		</tr>
		<tr>
			<td><b>To:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo htmlspecialchars($overview[0]->to); ?></td>
		</tr>
		<tr>
			<td><b>Date:</b>&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo htmlspecialchars($overview[0]->date); ?></td>
		</tr>
	</table>
</div>

<div class="message">
	<h2><?php echo htmlspecialchars($overview[0]->subject); ?></h2>
	<?php echo $message; ?>
</div>

<?php 
	// If the sender is in the list, provide a "Reply to Email" link
	if (in_array($overview[0]->from,$to_email) && !isset($_GET['mbox'])) {
		echo "<a href=\"compose.php?uid=".$_GET['uid']."\"><div class=\"message-footer\">Reply to E-mail</div></a>\n";
	}

	include 'footer.php';
?>