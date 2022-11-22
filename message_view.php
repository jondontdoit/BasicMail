<?php
  // A message ID is required for this page - if one hasn't been provided, return to the Inbox
  if (!isset($_GET['uid']) || !is_numeric($_GET['uid'])) {
    header("Location: index.php");
    die;
  }

  require 'config.php';
  require 'connect.php';
	require 'message_functions.php';

  $uid = $_GET['uid'];

  // Fetch message
  $overview = imap_fetch_overview($mconn,$uid,FT_UID);
  $header = imap_fetchheader($mconn,$uid,FT_UID);
  $structure = imap_fetchstructure($mconn, $uid, FT_UID);
  $message = get_message($mconn,$uid);

  if ($mbox == 'sent') {
    $message_person = imap_utf8($overview[0]->to);
  } else {
    $message_person = imap_utf8($overview[0]->from);
  }
  $message_date = htmlspecialchars($overview[0]->date);
  $message_subject = htmlspecialchars($overview[0]->subject);

  // Process any attachments
  $attachments = array();
  $attachments = get_attachments($structure);

  // Check if the sender is in the list - if so, allow reply
	if ($mbox == 'inbox' && check_email($message_person, $allowed_email)) {
		$reply_enable = true;
	} else {
		$reply_enable = false;
	}

  require 'header.php';
?>
  <div class="message-header">
    <span><?php echo ($mbox == 'sent' ? 'To' : 'From'); ?>:</span>&nbsp;&nbsp;<?php echo htmlspecialchars($message_person); ?><br />
    <span>Date:</span>&nbsp;&nbsp;&nbsp;<?php echo $message_date; ?>
  </div>

  <div class="message">
    <h1><?php echo $message_subject; ?></h1>
    <?php echo $message."\n"; ?>
  </div>

<?php
  // Show attachments, if any
  if ($show_attachments && count($attachments) > 0) {
    echo '  <div class="message-attachments">'."\n";
    echo '    <h1>Attachment(s):</h1>'."\n";
    echo '    <ul>'."\n";

    foreach ($attachments as $k => $at) {
      $filename = $at['filename'];
      echo '      <li><a href="message_view_attachment.php?uid='.$uid.'&filename='.$filename.'" target="_blank">'.$filename.'</a></li>'."\n";
    }

    echo '    </ul>'."\n";
    echo '  </div>'."\n\n";
  }

  if ($reply_enable) {
    echo '  <div class="action-button"><a href="message_reply.php?uid='.$uid.'"><div>Reply to Message</div></a></div>'."\n";
  }

  require 'footer.php';
?>