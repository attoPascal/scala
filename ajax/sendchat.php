<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if ((isset($_POST['text']) && $_POST['text'] !== "")) {
    $uid      = $_SESSION['user']['uid'];
    $nick     = $_SESSION['user']['nick'];
    $text     = nl2br(htmlspecialchars($_POST['text']));
    $rec_id   = $_POST['rec_id'];
    $time     = date("Y-m-d H:i:s", time());
    //$reply_id = $_POST['reply_id'];
    
    $sql = "INSERT INTO `scala_chat` (
      `id`,
      `send_id`,
      `rec_id`,
      `text`,
      `date`
    ) VALUES (
      NULL, '".$uid."', '".$rec_id."', '".$text."', NULL
    );";
    $query   = mysql_query($sql);
    $mssg_id = mysql_insert_id();
    
    echo "<div class=\"message\" id=\"message".$mssg_id."\">\n";
    echo "  <div class=\"nick\"><b>".$nick."</b></div>\n";
    echo "  <div class=\"text\">".$text."</div>\n";
    //echo "  <div>".$date."</div>\n";
    echo "<br /></div>\n";
  }
?>