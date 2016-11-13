<?php
	/* try to connect */
	$inbox = imap_open($hostname.'INBOX',$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
?>
<html>
<head>
<title><?php echo $title_html; ?></title>
<link rel="stylesheet" type="text/css" href="basicmail.css" />
</head>

<body<?php if (isset($onload)) echo $onload; ?>>

	<div class="header">
	<table border="0" width="100%">
		<tr>
			<td class="header-title" align="center" valign="middle"><?php echo $title_page; ?></td>
			<td class="header-inbox" align="center" valign="middle"><a href="index.php"><div class="header-link">View Inbox</div></a></td>
			<td class="header-sent" align="center" valign="middle"><a href="sent.php"><div class="header-link">View Sent Mail</div></a></td>
			<td class="header-compose" align="center" valign="middle"><a href="compose.php"><div class="header-link">New E-mail</div></a></td>
		</tr>
	</table>
	</div>

<center>