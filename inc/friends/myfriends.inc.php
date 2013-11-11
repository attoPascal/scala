<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  require_once("../dbc.inc.php");
  
  echo "<h1>Meine Freunde</h1>\n";
  
  $friends = $_SESSION['user']['friends'];
  $fids    = $friends ? join(',',$friends) : 0;
  
  $sql = "SELECT * FROM `scala_users` WHERE `id` IN (".$fids.") ORDER BY `nick` ASC";
  $query = mysql_query($sql);
  
  if ($query && mysql_num_rows($query) > 0) {  
    while ($entry = mysql_fetch_array($query)) {
      $fid        = $entry['id'];
      $fnick      = $entry['nick'];
      $ffirstname = $entry['firstname'];
      $flastname  = $entry['lastname'];
      $ffriends   = unserialize($entry['friends']);
      
      echo "<div class=\"friend\">\n";
      echo "  <div class=\"nick\"><a href=\"?page=profile&id=".$fid."\" class=\"ajax\">".$fnick."</a></div>\n";
      echo "  <div>".$ffirstname." ".$flastname."</div>\n";
      echo "  <div>".count($ffriends)." Freund(e)</div>\n";
      echo "</div>\n";
    }
  } else {
    echo "<p>Du hast leider noch keine Freunde&hellip;<br />M&ouml;chtest du neue <a href=\"?page=friends&tab=1\">Freunde finden</a>?</p>";
  }
?>