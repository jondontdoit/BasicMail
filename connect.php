<?php
	// Defaults
	$mbox = 'inbox';
	$hbox = 'INBOX';

	// Switch to sent box if applicable
	if (isset($_GET['mbox']) && $_GET['mbox'] == 'sent') {
		$hbox = 'INBOX.Sent';
		$mbox = 'sent';
	}

	// Switch to archive if applicable
	if (isset($_GET['mbox']) && $_GET['mbox'] == 'archive') {
		$hbox = 'INBOX.old-messages';
		$mbox = 'archive';
	}

	$mconn = imap_open($hostname.$hbox,$username,$password) or die('Cannot connect to Mail Server: ' . imap_last_error());
?>