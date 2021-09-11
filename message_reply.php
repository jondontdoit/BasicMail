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
  $overview = imap_fetch_overview($inbox,$uid,FT_UID);
  if ($reply_inline) {
    $message = get_message_plaintext($inbox,$uid);
  } else {
    $message = get_message($inbox,$uid);
  }

  $message_from = imap_utf8($overview[0]->from);
  $message_date = htmlspecialchars($overview[0]->date);
  $message_subject = htmlspecialchars($overview[0]->subject);

  // If the from address isn't in the allowed list, return to the Inbox
  if (!in_array(strtolower($message_from),array_map('strtolower',$allowed_email))) {
    header("Location: index.php");
    die;
  }

  // Focus on the appropriate input element
  $onLoad = "document.getElementById('message-body').focus();";

  require 'header.php';
?>
  <form name="compose" id="compose" action="./message_send.php" method="POST">

    <div class="message-header">
      <span>To:</span>&nbsp;&nbsp;<?php echo htmlspecialchars($message_from); ?><br />
    </div>
    <input type="hidden" name="message-to" value="<?php echo $message_from; ?>" />
    <input type="hidden" name="message-subject" value="<?php
      echo (stristr($message_subject,'Re: ') === FALSE ? 'Re: ' : '').$message_subject;
    ?>" />

    <div class="message">
      <h1><?php echo $message_subject; ?></h1>
      <textarea name="message-body" id="message-body"><?php
        if ($reply_inline) {
          echo "\n\n\n\n----- Reply To -----\n";
          echo "Date: ".$message_date."\n";
          echo "Subject: ".$message_subject."\n\n";
          echo $message."\n\n";
        }
      ?></textarea>
      <?php if (!$reply_inline) echo '<br /><br />'.$message."\n"; ?>
    </div>

    <input type="hidden" name="send" value="Send Message" />
    <div class="action-button"><a href="javascript:checkForm();"><div>Send Message</div></a></div>

  </form>

  <script type="text/javascript">
    // Validate that everything is filled out correctly
    function checkForm() {
      var isGood = true;

      // Make sure something was written in the message box
      if (isGood && document.getElementById('message-body').value.trim()<?php echo ($reply_inline ? ".indexOf('----- Reply To -----') == 0" : " == ''"); ?>) {
        isGood = false;
        alert('You need to write a message!');
        document.getElementById('message-body').focus();
      }

      if (isGood) document.forms['compose'].submit();
    }
  </script>
<?php
  require 'footer.php';
?>