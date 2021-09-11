<?php
	////////////////////////////////////////////////////////////////////////////
	// Gmail Settings
	////////////////////////////////////////////////////////////////////////////
	$hostname = "{imap.gmail.com:993/imap/ssl/novalidate-cert}";
	//$hostname = "{64.233.191.109:993/imap/ssl/novalidate-cert}"; // I found using the IP address more reliable
	$username = '[YOUR_USERNAME]@gmail.com';
	$password = '[YOUR_GMAIL_PASSWORD]';
	$smtp_host = 'smtp.gmail.com';
	$smtp_port = 587;
	$smtp_auth = true;
	$from_name = '[YOUR_FULL_NAME]';
	$from_email = $from_name.' <'.$username.'>';
	//imap_timeout(IMAP_OPENTIMEOUT, 180); //Play with this if you're having occasional timeout issues


	////////////////////////////////////////////////////////////////////////////
	// Site Settings
	////////////////////////////////////////////////////////////////////////////
	$title_html = 'BasicMail'; // Text for <title></title> tags
	$title_page = 'BasicMail'; // Text for the top of each page
	$list_size_default =  7; // Limit number of messages shown in inbox/outbox
	$list_size_maximum =  30; // Most messages to show if "View All" selected
	$show_attachments = true; // Ignore attachments
	$error_email = 'Jane Doe <doe.jane@yahoo.com>'; // Address to send errors to
	$default_subject = '[No Subject]'; // Default New Email Subject
	$reply_inline = false; // Set to true to put the original message in the reply textarea

	// Valid sending addresses
	$allowed_email = array();
	array_push($allowed_email, 'John Doe <doe.john@gmail.com>');
	array_push($allowed_email, 'Jane Doe <doe.jane@yahoo.com>');

	// Sort the addresses alphabetically
	sort($allowed_email,SORT_STRING);
