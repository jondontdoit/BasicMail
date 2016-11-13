<?php

	/* Gmail settings */
	//imap_timeout(IMAP_OPENTIMEOUT, 180); //Play with this if you're having occasional timeout issues
	$hostname = "{imap.gmail.com:993/imap/ssl/novalidate-cert}";
	$username = '[YOUR_USERNAME]@gmail.com';
	$password = '[YOUR_GMAIL_PASSWORD]';
	$from_name = '[YOUR_FULL_NAME]';
	$from_email = $from_name.' <'.$username.'>';

	/* Site Settings */
	$title_html = 'BasicMail Beta'; //Text for <title></title> tags
	$title_page = 'BasicMail'; //Text for the top of each page

	/* mail settings */
	
	//Valid sending addresses
	$to_email = array();
	$to_email[0] = 'John Doe <doe.john@gmail.com>';
	$to_email[1] = 'Jane Doe <doe.jane@yahoo.com>';
	
	//Sort the addresses alphabetically
	sort($to_email,SORT_STRING);
	
	//Default account to send errors to
	$default_email = $to_email[0];
?>