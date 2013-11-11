<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if (isset($_GET['fid']) && $_GET['fid'] !== "") {
    $fid = $_GET['fid'];
    $uid = $_SESSION['user']['uid'];
    
    $sql = 
      "SELECT c.`id`, c.`send_id`, c.`rec_id`, c.`text`, c.`date`, u.`nick`
      FROM `scala_users` AS u, `scala_chat` AS c
      WHERE (`send_id` = '".$uid."' AND `rec_id`= '".$fid."' AND c.`send_id` = u.`id`) 
      OR    (`send_id` = '".$fid."' AND `rec_id`= '".$uid."' AND c.`send_id` = u.`id`)
      ORDER BY c.`id` ASC";
    $query = mysql_query($sql);
    
    echo "<div class=\"chats\" id=\"chats".$fid."\">\n";
    if (mysql_num_rows($query) > 0) {  
      while ($entry = mysql_fetch_array($query)) {
        $mssg_id = $entry['id'];
        $send_id = $entry['send_id'];
        $rec_id  = $entry['rec_id'];
        $text    = $entry['text'];
        $date    = $entry['date'];
        $nick    = $entry['nick'];
          
        echo "<div class=\"message\" id=\"message".$mssg_id."\">\n";
        echo "  <div class=\"nick\"><b>".$nick."</b></div>\n";
        echo "  <div class=\"text\">".$text."</div>\n";
        //echo "  <div>".$date."</div>\n";
        echo "<br /></div>\n";
      }
      echo "</div>\n";
      
      echo "<div class=\"input\">\n";
      echo "  <input type=\"text\" id=\"input".$fid."\" />\n";
      echo "</div>\n";
    } else {
      echo "<div class=\"message hide\" id=\"message0\"></div>\n";
      echo "</div>\n";
      echo "<div class=\"input\">\n";
      echo "  <input type=\"text\" id=\"input".$fid."\" />\n";
      echo "</div>\n";
    }
  } else {
    echo "Unknown Error: Keine ID &uml;bermittelt";
  }
?>