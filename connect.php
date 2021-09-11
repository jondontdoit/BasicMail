<?php
	// Try to Connect
	$inbox = imap_open($hostname.'INBOX',$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
	$mbox = 'inbox';

	// Switch to sent box if applicable
	if (isset($_GET['mbox']) && $_GET['mbox'] == 'sent') {
		imap_reopen($inbox,$hostname.'[Gmail]/Sent Mail');
		$mbox = 'sent';
	}
?>