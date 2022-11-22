<?php
  // A message ID and filename are required for this page - if they haven't been provided, return to the Inbox
	if (!isset($_GET['uid']) || !is_numeric($_GET['uid']) || !isset($_GET['filename'])) {
		header("Location: index.php");
		die;
	}

	require 'config.php';
	require 'connect.php';
	require 'message_functions.php';

	$uid = $_GET['uid'];
	$filename = $_GET['filename'];

	// Fetch message
	$structure = imap_fetchstructure($mconn, $uid, FT_UID);

	// Process attachments
	$attachments = array();
	$attachments = get_attachments($structure);

	if ($show_attachments && count($attachments) > 0) {
		foreach ($attachments as $k => $at) {
			if ($at['filename'] == $filename) {

				header("Content-Type: ".$at['type']);
				header("Content-Transfer-Encoding: ".$at['encoding']);
				header("Content-Disposition: inline; filename=".$at['filename']);

				$content = imap_fetchbody($mconn,$uid,$at['part'],FT_UID);

				if ($content !== false && strlen($content) > 0 && $content != '') {
					if ($at['encoding'] == '3') $content = base64_decode($content);
					if ($at['encoding'] == '4') $content = quoted_printable_decode($content);

					echo $content;
					die;
				}
			}
		}
	}
