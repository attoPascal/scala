<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if (isset($_POST['text']) && $_POST['text'] !== "") {
    $uid      = $_SESSION['user']['uid'];
    $nick     = $_SESSION['user']['nick'];
    $text     = nl2br(htmlspecialchars($_POST['text']));
    $rec_id   = $_POST['rec_id'];
    $time     = date("Y-m-d H:i:s", time());
    //$reply_id = $_POST['reply_id'];
    
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
    $sid   = mysql_insert_id();
    
    if ($rec_id) {
      $sql = "SELECT `nick` FROM `scala_users` WHERE `id` = '".$rec_id."' LIMIT 1";
      $entry = mysql_fetch_array(mysql_query($sql));
      
      $recipient = $entry['nick'];
      
      $plink = "<a href=\"?page=profile&amp;id=".$uid."\">".$nick."</a> &#x25b6; <a href=\"?page=profile&amp;id=".$rec_id."\">".$recipient."</a>";
    } else { //eigener Post
      $plink = "<a href=\"?page=profile&amp;id=".$uid."\">".$nick."</a>";
    }
    
    echo "<div class=\"new status\" id=\"status".$sid."\">
      <div class=\"nick\">".$plink."</div>
      <div class=\"text\">".$text."</div>
      <div class=\"time\">".$time."</div>
    </div>";
  }
?>