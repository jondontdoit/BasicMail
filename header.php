<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
<head>
  <title><?php echo $title_html; ?></title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="Resource-type" content="Document" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
  <link rel="manifest" href="/site.webmanifest" />
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5" />
  <meta name="msapplication-TileColor" content="#da532c" />
  <meta name="theme-color" content="#ffffff" />

  <link rel="stylesheet" type="text/css" href="css/site.css" />
</head>

<body<?php if (isset($onLoad)) echo ' onLoad="'.$onLoad.'"'; ?>>

  <header>
    <nav>
      <ul>
        <li><?php
          if (isset($reply_enable) && $reply_enable) {
            echo '<a href="message_reply.php?uid='.$_GET['uid'].'"><div>Reply to Message</div></a>';
          } else {
            echo '<a href="message_compose.php"><div>New Message</div></a>';
          }
        ?></li>
        <li><a href="index.php"><div>Inbox</div></a></li>
        <li><a href="index.php?mbox=sent"><div>Sent Mail</div></a></li>
      </ul>
    </nav>
    <div class="header-title"><?php echo $title_page; ?></div>
  </header>

