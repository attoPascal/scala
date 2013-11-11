<?php
  session_start();
  require_once("dbc.inc.php");
  
  if (isset($_POST['submit_status']) && ($_POST['status'] !== "")) {
    $uid    = $_SESSION['user']['uid'];
    $text   = nl2br(htmlspecialchars($_POST['status']));
    $rec_id = $_POST['recipient'];
    
    $sql = "INSERT INTO `scala_status` (
      `id`,
      `uid`,
      `text`,
      `time`,
      `rec_id`,
      `reply_id`
    ) VALUES (
      NULL, '".$uid."', '".$text."', NULL, '".$rec_id."', ''
    );";
    $query = mysql_query($sql);
  }
  
  if (isset($_GET['id']) && $_GET['id'] !== $_SESSION['user']['uid']) {
    $id = $_GET['id'];
    
    $sql = "SELECT `nick`, `firstname`, `lastname` FROM `scala_users` WHERE `id` = '".$id."'";
    $query = mysql_query($sql);
    
    if (mysql_num_rows($query) > 0) {
      $entry = mysql_fetch_array($query);
    } else {
      die ("ERROR: Profil nicht vorhanden");
    }
    
    $nick      = $entry['nick'];
    $firstname = $entry['firstname'];
    $lastname  = $entry['lastname'];
    $rec_id    = $id;
  } else {
    $id        = $_SESSION['user']['uid'];
    $nick      = $_SESSION['user']['nick'];
    $firstname = $_SESSION['user']['firstname'];
    $lastname  = $_SESSION['user']['lastname'];
    $rec_id    = 0;
    $myprofile = true;
  }

  echo "<h1>".$nick." <span style=\"font-weight:normal\">(".$firstname."&nbsp;".$lastname.")</span></h1>\n";
  
  echo "<div id=\"newstatus\">\n";
  echo "  <form action=\"?page=profile\" method=\"post\">\n";
  echo "    <textarea name=\"status\" id=\"status\"></textarea>\n";
  echo "    <input type=\"hidden\" name=\"recipient\" id=\"recipient\" value=\"".$rec_id."\" />\n";
  echo "    <div class=\"hide\"><input type=\"submit\" value=\"Teilen\" name=\"submit_status\" id=\"submit_status\" /></div>\n";
  echo "  </form>\n";
  echo "</div>\n";

  $sql = "SELECT * FROM `scala_status` WHERE (`uid` = '".$id."') OR (`rec_id` = '".$id."') ORDER BY `time` DESC LIMIT 25";
  $query = mysql_query($sql);
  
  if ($query && mysql_num_rows($query) > 0) {
    echo "<p>Beitr&auml;ge von und mit <b>".$nick."</b>:</p>\n";
    echo "<div class=\"feed\" id=\"feed".$id."\">\n";
  
    while ($entry = mysql_fetch_array($query)) {
      $sid    = $entry['id'];
      $uid    = $entry['uid'];
      $text   = $entry['text'];
      $time   = $entry['time'];
      $rec_id = $entry['rec_id'];
      
      if ($rec_id == $id) { //aktuelles Profil ist Empfaenger
        $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$uid."' LIMIT 1";
        $entry = mysql_fetch_array(mysql_query($sql));
        
        $sender = $entry['nick'];
        
        $plink = "<a href=\"?page=profile&amp;id=".$uid."\" class=\"ajax\">".$sender."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$id."\" class=\"ajax\">".$nick."</a>";
      } elseif ($rec_id) { //aktuelles Profil ist Versender
        $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$rec_id."' LIMIT 1";
        $entry = mysql_fetch_array(mysql_query($sql));
        
        $recipient = $entry['nick'];
        
        $plink = "<a href=\"?page=profile&amp;id=".$id."\" class=\"ajax\">".$nick."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$rec_id."\">".$recipient."</a>";
      } else { //eigener Post
        $plink = "<a href=\"?page=profile&amp;id=".$id."\" class=\"ajax\">".$nick."</a>";
      }
      
      echo "<div class=\"status\" id=\"status".$sid."\">\n";
      echo "  <div class=\"nick\">".$plink."</div>\n";
      echo "  <div class=\"text\">".$text."</div>\n";
      echo "  <div class=\"time\">".$time."</div>\n";
      echo "</div>\n"; //<a href=\"#replyto".$sid."\">Antworten</a>
    }
    
    echo "</div>\n";
    echo "<div class=\"more\"><br /><a href=\"#\" class=\"button\">&Auml;ltere Beitr&auml;ge laden&hellip;</a></div>\n";
  } else {
    echo "<div class=\"feed\" id=\"feed".$id."\"><p>Es gibt noch keine Beitr&auml;ge von/mit <b>".$nick."</b></p></div>";
  }
?>