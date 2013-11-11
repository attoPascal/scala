<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  require_once("../dbc.inc.php");
  
  $uid     = $_SESSION['user']['uid'];
  $friends = $_SESSION['user']['friends'];
  $fids    = $friends ? join(',',$friends) : 0;
  
  echo "<h1>Freunde finden</h1>";
  
  $sql = "SELECT * FROM `scala_users` WHERE `id` NOT IN (".$uid.",".$fids.") ORDER BY `nick` ASC";
  $query = mysql_query($sql);
  
  if ($query && mysql_num_rows($query) > 0) {  
    while ($entry = mysql_fetch_array($query)) {
      $fid        = $entry['id'];
      $fnick      = $entry['nick'];
      $ffirstname = $entry['firstname'];
      $flastname  = $entry['lastname'];
      $ffriends   = unserialize($entry['friends']);
      $fnum       = $ffriends ? count($ffriends) : "0";
      $bclass     = "";
      
      $sql2 = "SELECT * FROM `scala_reqs` WHERE `send_id` = '".$uid."' AND `rec_id` = '".$fid."' LIMIT 1;";
      $query2 = mysql_query($sql2);
      if (mysql_num_rows($query2) > 0) {
        $bclass = " sent";
      }
      
      $sql3 = "SELECT * FROM `scala_reqs` WHERE `send_id` = '".$fid."' AND `rec_id` = '".$uid."' LIMIT 1;";
      $query3 = mysql_query($sql3);
      if (mysql_num_rows($query3) > 0) {
        $bclass = " recieved";
      }
      
      echo "<div class=\"friend\">\n";
      echo "  <div class=\"floatbuttons\"><a href=\"?page=friends&fid=".$fid."\" class=\"button addfriend".$bclass."\">Anfrage senden</a></div>\n";
      echo "  <div class=\"nick\"><a href=\"?page=profile&id=".$fid."\" class=\"ajax\">".$fnick."</a></div>\n";
      echo "  <div>".$ffirstname." ".$flastname."</div>\n";
      echo "  <div>".$fnum." Freund(e)</div>\n";
      echo "</div>\n";
    }
  } else {
    echo "<p>Du bist schon mit allen befreundet, jetzt reichts dann aber!</p>";
  }
?>