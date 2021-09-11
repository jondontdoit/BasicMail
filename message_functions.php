<?php

	// Use this function to get the message in whatever format it's in
  function get_message($inbox, $uid) {

	  $header = imap_fetchheader($inbox,$uid,FT_UID);
	  $structure = imap_fetchstructure($inbox, $uid, FT_UID);
	  $message = '';

	  // Process the message body
	  if (!(stristr($header, 'Content-Type: text/plain') === FALSE)) {
	    $message = imap_fetchbody($inbox,$uid,1,FT_UID);
	    if ($structure->encoding == "3") {
	      $message = base64_decode($message);
	    } elseif ($structure->encoding == "4") {
	      $message = imap_qprint($message);
	    }
	    $message = nl2br(htmlspecialchars($message));
	  } else if (!(stristr($header, 'Content-Type: multipart/mixed') === FALSE) || !(stristr($header, 'Content-Type: multipart/related') === FALSE)) {
	    $message = imap_fetchbody($inbox,$uid,1.1,FT_UID);
	    $message = nl2br(htmlspecialchars($message));
	  } else {
	    $message = imap_fetchbody($inbox,$uid,2,FT_UID);
	    if ($message == '') {
	      $message = imap_fetchbody($inbox,$uid,1,FT_UID);
	    }
	  }
	  $message = quoted_printable_decode($message);
	  $message = str_replace('="mailto:','="index.php" title="',$message);

    return $message;
  }


	// Use this function to get the message in plaintext (good for inserting into the reply textarea)
  function get_message_plaintext($inbox, $uid) {

    $message = imap_fetchbody($inbox,$uid,2,FT_UID);
    if ($message == '') $message = imap_fetchbody($inbox,$uid,1,FT_UID);

    $message = str_replace(array('<br />','<br>'),"\n",$message);
    $message = strip_tags(htmlspecialchars_decode($message));
    $message = str_replace(array("=\r\n",' =A0'),'',$message);
    $message = str_replace("=\r\n",'',$message);

    while (true) {
      preg_match('/=[0-9,A-F][0-9,A-F]/', $message, $matches, PREG_OFFSET_CAPTURE);
      if (!isset($matches) || !isset($matches[0]) || count($matches[0]) < 1) {
        break;
      }
      if (isset($matches[0]) && isset($matches[0][0])) {
        $message = str_replace($matches[0][0],'',$message);
      }
    }

    return $message;
  }

  /**
  * Gets all attachments
  * Including inline images or such
  * @author: Axel de Vignon
  * @param $content: the email structure
  * @param $part: not to be set, used for recursivity
  * @return array(type, encoding, part, filename)
  * Credit: http://vidax.net/blog/2012/01/php-how-to-extract-attachments-from-email/
  */
  function get_attachments($content, $part = null, $skip_parts = false) {
    static $results;

    // First round, emptying results
    if (is_null($part)) {
      $results = array();
    }
    else {
      // Removing first dot (.)
      if (substr($part, 0, 1) == '.') {
        $part = substr($part, 1);
      }
    }

    // Saving the current part
    $actualpart = $part;
    // Split on the "."
    $split = explode('.', $actualpart);

    // Skipping parts
    if (is_array($skip_parts)) {
      foreach ($skip_parts as $p) {
        // Removing a row off the array
        array_splice($split, $p, 1);
      }
      // Rebuilding part string
      $actualpart = implode('.', $split);
    }

    // Each time we get the RFC822 subtype, we skip
    // this part.
    if (strtolower($content->subtype) == 'rfc822') {
      // Never used before, initializing
      if (!is_array($skip_parts)) {
        $skip_parts = array();
      }
      // Adding this part into the skip list
      array_push($skip_parts, count($split));
    }

    // Checking ifdparameters
    if (isset($content->ifdparameters) && $content->ifdparameters == 1 && isset($content->dparameters) && is_array($content->dparameters)) {
      foreach ($content->dparameters as $object) {
        if (isset($object->attribute) && preg_match('~filename~i', $object->attribute)) {
          $results[] = array(
          'type'      => (isset($content->subtype)) ? $content->subtype : '',
          'encoding'    => $content->encoding,
          'part'      => empty($actualpart) ? 1 : $actualpart,
          'filename'    => $object->value
          );
        }
      }
    }

    // Checking ifparameters
    else if (isset($content->ifparameters) && $content->ifparameters == 1 && isset($content->parameters) && is_array($content->parameters)) {
      foreach ($content->parameters as $object) {
        if (isset($object->attribute) && preg_match('~name~i', $object->attribute)) {
          $results[] = array(
          'type'      => (isset($content->subtype)) ? $content->subtype : '',
          'encoding'    => $content->encoding,
          'part'      => empty($actualpart) ? 1 : $actualpart,
          'filename'    => $object->value
          );
        }
      }
    }

    // Recursivity
    if (isset($content->parts) && count($content->parts) > 0) {
      // Other parts into content
      foreach ($content->parts as $key => $parts) {
        get_attachments($parts, ($part.'.'.($key + 1)), $skip_parts);
      }
    }
    return $results;
  }
?>