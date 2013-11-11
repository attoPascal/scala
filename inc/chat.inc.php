<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  require_once("dbc.inc.php");
  
  $uid = $_SESSION['user']['uid'];
?>
  <h1>Chat</h1>
  <div style="margin-bottom:15px"><a href="#" class="button" id="newchat">Neuer Chat</a></div>
  
  <div id="chat">
<?php
    $sql = 
      "SELECT u.`id`, u.`nick`, c.`text`, c.`date`
      FROM  `scala_chat` AS c, `scala_users` AS u
      WHERE (
        (c.`send_id` = '".$uid."' AND c.`rec_id` = u.`id`)
        OR
        (c.`rec_id` = '".$uid."' AND c.`send_id` = u.`id`)
      )
      AND c.`id` = (
        SELECT MAX(c2.`id`)
        FROM `scala_chat` AS c2 , `scala_users` AS u2
        WHERE (
          (c2.`send_id` = '".$uid."' AND c2.`rec_id` = u2.`id`)
          OR
          (c2.`rec_id` = '".$uid."' AND c2.`send_id` = u2.`id`)
        )
        AND (u2.`nick` = u.`nick`)
      )
      ORDER BY c.`date` DESC";
    $query = mysql_query($sql);
    
    if (mysql_num_rows($query) > 0) {  
      while ($entry = mysql_fetch_array($query)) {
        $fid       = $entry['id'];
        $fnick     = $entry['nick'];
        $last_mssg = $entry['text'];
        $last_date = $entry['date'];
          
        echo "<div>\n";
        echo "  <h3 id=\"chat".$fid."\"><a href=\"#\">".$fnick." <span class=\"last_mssg\">".$last_mssg."</span></a></h3>\n";
        echo "  <div>\n";
        echo "  </div>\n";
        echo "</div>\n";
      }
    } else {
      echo "<div>Du hast noch keine Chats.</div>";
    }
    
    
?>
  </div>