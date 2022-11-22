<?php
  require 'config.php';
  require 'connect.php';

  // Determine how many messages to show
  $view = $list_size_default;
  if (isset($_GET['view']) && $_GET['view'] == 'all') $view = $list_size_maximum;

  // Fetch all messages and sort by newest
  //$emails = imap_sort($mconn, SORTARRIVAL, true, SE_UID, 'ALL');
  $emails = imap_search($mconn,'ALL'); rsort($emails);

  require 'header.php';

  // Build List
  $output = '';

  if ($emails) {

    // List Header
    $output .= '  <div class="list-header">'."\n";
    $output .= '    <div class="who">'.($mbox == 'inbox' ? 'From' : 'To').'</div>'."\n";
    $output .= '    <div class="subject">Subject</div>'."\n";
    $output .= '    <div class="date">Date</div>'."\n";
    $output .= '  </div>'."\n\n";

    // List Counter
    $count = 0;

    // Process each message
    foreach($emails as $email_number) {

      // If we're still within the list size
      if ($count < $view) {

        // Fetch message overview
        $overview = imap_fetch_overview($mconn,$email_number,0);

        // Output the message details
        $output .= '  <a href="message_view.php?'.($mbox == 'sent' ? 'mbox=sent&' : '').'uid='.$overview[0]->uid.'">'."\n";
        $output .= '    <div class="list-'.(($count % 2) ? 'odd' : 'even').'">'."\n";
        $output .= '      <span class="'.($overview[0]->seen ? 'read' : 'unread').'">'."\n";
        $output .= '        <div class="who">'.ucwords(strip_tags(($mbox == 'inbox' ? $overview[0]->from : $overview[0]->to))).'</div>'."\n";
        $output .= '        <div class="subject">'.imap_utf8($overview[0]->subject).'</div>'."\n";
        $output .= '        <div class="date">'.date('Y-m-d H:i:s',strtotime(str_replace(' UT', ' UTC', $overview[0]->date))).'</div>'."\n";
        $output .= '      </span>'."\n";
        $output .= '    </div>'."\n";
        $output .= '  </a>'."\n\n";
      }

      $count++;
    }

    // List Footer
    $output .= '  <div class="list-footer">&nbsp;</div>'."\n\n";

    // Include "View All" button if not currently viewing all
    if ($view != $list_size_maximum) {
      $output .= '  <div class="action-button"><a href="index.php?'.($mbox == 'sent' ? 'mbox=sent&' : '').'view=all"><div>View All Messages</div></a></div>'."\n";
    }

  // If there are no emails to display
  } else {
    $output .= '  <div class="message">You have no messages</div>'."\n\n";
    $output .= '  <div class="action-button"><a href="index.php"><div>Refresh</div></a></div>'."\n";
  }

  // Display output
  echo $output;

  require 'footer.php';
