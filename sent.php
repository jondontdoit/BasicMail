<?php
	/************************************
		DISPLAYS THE OUTBOX
	************************************/
	
	require 'config.php';
	require 'header.php';

	// Open the sent mailbox
	imap_reopen($inbox,$hostname.'Sent');

	/* grab emails */
	$emails = imap_search($inbox,'ALL');

	/* if emails are returned, cycle through each... */
	if($emails) {
	  
	  /* begin output var */
	  $output = '<div class="list-header">'."\n";
	  $output.= "\t".'<div class="from">To</div>'."\n";
	  $output.= "\t".'<div class="subject">Subject</div>'."\n";
	  $output.= "\t".'<div class="date" style="font-size: 12pt;">Date</div>'."\n";
	  $output.= '</div>'."\n\n";
	  
	  /* put the newest emails on top */
	  rsort($emails);
	  
	  /* init flip-flop */
	  $flip_flop = 0;
	  
	  /* init counter */
	  $count = 0;
	  
	  /* for every email... */
	  foreach($emails as $email_number) {
		
		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox,$email_number,0);
		//$message = imap_fetchbody($inbox,$email_number,2);
		
		/* output the email header information */    
		$output.= '<a href="view.php?mbox=sent&uid='.$overview[0]->uid.'">'."\n";
		$output.= '<div class="list-'.($flip_flop ? 'odd' : 'even').'">'."\n";
		$output.= "\t".'<span class="'.($overview[0]->seen ? 'read' : 'unread').'">'."\n";
		$output.= "\t\t".'<div class="from">'.strip_tags($overview[0]->to).'</div>'."\n";
		$output.= "\t\t".'<div class="subject">'.htmlspecialchars($overview[0]->subject).'</div>'."\n";
		$output.= "\t\t".'<div class="date">'.date('Y-m-d H:i:s',strtotime($overview[0]->date)).'</div>'."\n";
		$output.= "\t".'</span>'."\n";
		$output.= '</div>'."\n";
		$output.= '</a>'."\n\n";
		
		$count++;
		$flip_flop = !$flip_flop;
	  }
	  
	  echo $output;
	} 

	require 'footer.php';
?>