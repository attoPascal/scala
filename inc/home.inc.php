<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  require_once("dbc.inc.php");
  
  $nick      = $_SESSION['user']['nick'];
  $uid       = $_SESSION['user']['uid'];
  $firstname = $_SESSION['user']['firstname'];
  $lastname  = $_SESSION['user']['lastname'];
  $friends   = $_SESSION['user']['friends'];
  
  if (isset($_POST['submit_status']) && ($_POST['status'] !== "")) {
    $text = nl2br(htmlspecialchars($_POST['status']));
    
    $sql = "INSERT INTO `scala_status` (
      `id`,
      `uid`,
      `text`,
      `time`,
      `rec_id`,
      `reply_id`
    ) VALUES (
      NULL, '".$uid."', '".$text."', NULL, '', ''
    );";
    $query = mysql_query($sql);
  }
?>
  <h1>Feed</h1>
  
  <div id="newstatus">
    <form action="?page=home" method="post">
      <textarea name="status" id="status"></textarea>
      <div class="hide"><input type="submit" value="Teilen" name="submit_status" id="submit_status" /></div>
    </form>
  </div>


<?php
  $friends[] = $uid;
  $fids      = join(',',$friends);
  
  echo "<div class=\"feed\" id=\"feed0\">\n";
  
  $sql = "SELECT * FROM `scala_status` WHERE `uid` IN (".$fids.") ORDER BY `time` DESC LIMIT 25";
  $query = mysql_query($sql);
  
  if ($query && mysql_num_rows($query) > 0) {  
    while ($entry = mysql_fetch_array($query)) {
      $sid    = $entry['id'];
      $fid    = $entry['uid'];
      $text   = $entry['text'];
      $time   = $entry['time'];
      $rec_id = $entry['rec_id'];
      
      if ($rec_id && !in_array($rec_id, $friends)) { //Nachricht von Freund an Nicht-Freund: uebergehen
        continue;
      } elseif ($rec_id) { //Nachricht von Freund an Freund
        $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$fid."'";
        $entry = mysql_fetch_array(mysql_query($sql));
        $sender = $entry['nick'];
        
        $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$rec_id."'";
        $entry = mysql_fetch_array(mysql_query($sql));
        $recipient = $entry['nick'];
        
        $plink = "<a href=\"?page=profile&amp;id=".$fid."\" class=\"ajax\">".$sender."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$rec_id."\" class=\"ajax\">".$recipient."</a>";
      } else { //Status eines Freundes
        $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$fid."'";
        $entry = mysql_fetch_array(mysql_query($sql));
        $fnick = $entry['nick'];
        
        $plink = "<a href=\"?page=profile&id=".$fid."\" class=\"ajax\">".$fnick."</a>";
      }
      
      echo "<div class=\"status\" id=\"status".$sid."\">\n";
      echo "  <div class=\"nick\">".$plink."</div>\n";
      echo "  <div class=\"text\">".$text."</div>\n";
      echo "  <div class=\"time\">".$time."</div>\n";
      echo "</div>\n"; //<a href=\"#replyto".$sid."\">Antworten</a>
    }
    
    echo "<div class=\"more\"><br /><a href=\"#\" class=\"button\">&Auml;ltere Beitr&auml;ge laden&hellip;</a></div>\n";
    echo "</div>";
  } else {
    echo "<p>Keine neuen Meldungen im Feed.</p>";
    echo "</div>";
  }
?>