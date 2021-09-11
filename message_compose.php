<?php
  require 'config.php';
  require 'connect.php';

  // Default new email subject
  $message_subject = $default_subject;

  // Focus on the appropriate input element
  $onLoad = "document.getElementById('message-to').focus();";

  require 'header.php';
?>
  <form name="compose" id="compose" action="./message_send.php" method="POST">

    <div class="message-header">
      <span>To:</span>&nbsp;&nbsp;
      <select name="message-to" id="message-to">
        <option value="none">--- Pick a Name ---</option>
<?php
        // Loop through all the allowed addresses
        foreach($allowed_email as $to_data) {
          echo '        <option value="'.$to_data.'">'.strip_tags($to_data).'&nbsp;&nbsp;&nbsp;</option>'."\n";
        }
      ?>
      </select>
    </div>

    <div class="message">
      <input type="text" name="message-subject" id="message-subject" value="<?php echo $message_subject; ?>" />
      <textarea name="message-body" id="message-body"></textarea>
    </div>

    <input type="hidden" name="send" value="Send Message" />
    <div class="action-button"><a href="javascript:checkForm();"><div>Send Message</div></a></div>

  </form>

  <script type="text/javascript">
    // Validate that everything is filled out correctly
    function checkForm() {
      var isGood = true;

      // Make sure a recipient has been selected
      if (isGood && document.getElementById('message-to').value == 'none') {
        isGood = false;
        alert('You need to pick a person to send your email to!');
        document.getElementById('message-to').focus();
      }

      // Uncomment this validation if you'd like the extra check
      //if (isGood && document.getElementById('message-subject').value == '<?php echo $message_subject; ?>') {
      //  if (!confirm('Do you want to send this message with the default subject?')) {
      //    isGood = false;
      //    document.getElementById('message-subject').select();
      //  }
      //}

      // Make sure something was written in the message box
      if (isGood && document.getElementById('message-body').value.trim() == '') {
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