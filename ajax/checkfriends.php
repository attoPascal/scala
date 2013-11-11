<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  $uid          = $_SESSION['user']['uid'];
  $sess_friends = $_SESSION['user']['friends'];
  
  $sql = "SELECT `friends` FROM `scala_users` WHERE `id` = '".$uid."' LIMIT 1";
  $query = mysql_query($sql);
  
  if (mysql_num_rows($query) == 1) {
    $entry = mysql_fetch_array($query);
    
    $db_friends = unserialize($entry['friends']);
    
    if ($sess_friends !== $db_friends) {
      $_SESSION['user']['friends'] = $db_friends;
      echo "friendslist updated";
    }
  }
?>