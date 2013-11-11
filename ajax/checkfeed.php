<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if ((isset($_GET['sid']) && $_GET['sid'] !== "") && (isset($_GET['pid']) && $_GET['pid'] !== "")) { 
    $new_sid = $_GET['sid'];
    $pid     = $_GET['pid'];
    
    echo "<div class=\"insert\">\n";
    
    if ($pid == 0) { //Home-Feed
      $uid       = $_SESSION['user']['uid'];
      $nick      = $_SESSION['user']['nick'];
      $friends   = $_SESSION['user']['friends'];
      $friends[] = $uid;
      $fids      = join(',',$friends);
      
      $sql = "SELECT * FROM `scala_status` WHERE `uid` IN (".$fids.") AND `id` > '".$new_sid."' ORDER BY `time` DESC";
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
            
            $plink = "<a href=\"?page=profile&amp;id=".$fid."\">".$sender."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$rec_id."\">".$recipient."</a>";
          } else { //Status eines Freundes
            $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$fid."'";
            $entry = mysql_fetch_array(mysql_query($sql));
            $fnick = $entry['nick'];
            
            $plink = "<a href=\"?page=profile&id=".$fid."\">".$fnick."</a>";
          }
          
          echo "<div class=\"status\" id=\"status".$sid."\">\n";
          echo "  <div class=\"nick\">".$plink."</div>\n";
          echo "  <div class=\"text\">".$text."</div>\n";
          echo "  <div class=\"time\">".$time."</div>\n";
          echo "</div>\n";
        }
      } else {
        return false;
      }
    } else { //Profil-Feed
      $sql = "SELECT `nick`, `firstname`, `lastname` FROM `scala_users` WHERE `id` = '".$pid."'";
      $query = mysql_query($sql);
      
      if (mysql_num_rows($query) > 0) {
        $entry = mysql_fetch_array($query);
      } else {
        die ("ERROR: Profil nicht vorhanden");
      }
      
      $nick = $entry['nick'];
      
      $sql = "SELECT * FROM `scala_status` WHERE ((`uid` = '".$pid."') OR (`rec_id` = '".$pid."')) AND `id` > '".$new_sid."' ORDER BY `time` DESC";
      $query = mysql_query($sql);
      
      if ($query && mysql_num_rows($query) > 0) {      
        while ($entry = mysql_fetch_array($query)) {
          $sid    = $entry['id'];
          $fid    = $entry['uid'];
          $text   = $entry['text'];
          $time   = $entry['time'];
          $rec_id = $entry['rec_id'];
          
          if ($rec_id == $pid) { //aktuelles Profil ist Empfaenger
            $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$fid."' LIMIT 1";
            $entry = mysql_fetch_array(mysql_query($sql));
            
            $sender = $entry['nick'];
            
            $plink = "<a href=\"?page=profile&amp;id=".$fid."\">".$sender."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$pid."\">".$nick."</a>";
          } elseif ($rec_id) { //aktuelles Profil ist Versender
            $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$rec_id."' LIMIT 1";
            $entry = mysql_fetch_array(mysql_query($sql));
            
            $recipient = $entry['nick'];
            
            $plink = "<a href=\"?page=profile&amp;id=".$pid."\">".$nick."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$rec_id."\">".$recipient."</a>";
          } else { //eigener Post
            $plink = "<a href=\"?page=profile&amp;id=".$pid."\">".$nick."</a>";
          }
          
          echo "<div class=\"status\" id=\"status".$sid."\">
            <div class=\"nick\">".$plink."</div>
            <div class=\"text\">".$text."</div>
            <div class=\"time\">".$time."</div>
          </div>";
        }
        
        echo "</div>";
      } else {
        return false;
      }
    }
    echo "</div>\n";
  } else {
    echo "Error: keine IDs &uuml;bergeben";
  }
?>