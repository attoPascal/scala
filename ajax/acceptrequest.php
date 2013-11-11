<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if (isset($_POST['req_id']) && $_POST['req_id'] !== "") {
    $req_id  = $_POST['req_id'];
    $uid     = $_SESSION['user']['uid'];
    $friends = $_SESSION['user']['friends'];
    
    //Anfragedaten erhalten
    $sql = 
      "SELECT r.`send_id`, u.`nick`, u.`firstname`, u.`lastname`
      FROM `scala_users` AS u, `scala_reqs` AS r
      WHERE r.`id` = '".$req_id."' AND r.`send_id` = u.`id`";
    $query = mysql_query($sql);
  
    if (mysql_num_rows($query) > 0) {  
      while ($entry = mysql_fetch_array($query)) {
        $send_id    = $entry['send_id'];
        $fnick      = $entry['nick'];
        $ffirstname = $entry['firstname'];
        $flastname  = $entry['lastname'];
      }
      
      //Anfrage bestätigen
      $sql = 
        "UPDATE `scala_reqs`
        SET `accepted` = '1', `acc_date` = NOW( )
        WHERE `id` = '".$req_id."'";
      $query = mysql_query($sql);
      
      //Freundschaftsverbindungen herstellen
      $friends[] = $send_id;
      $_SESSION['user']['friends'] = $friends;
      
      $sql = "UPDATE `scala_users` SET `friends` = '".serialize($friends)."' WHERE `id` = '".$uid."'";
      $query = mysql_query($sql);
      
      $sql = "SELECT `friends` FROM `scala_users` WHERE `id` = '".$send_id."' LIMIT 1;";
      $entry = mysql_fetch_array(mysql_query($sql));
      
      $ffriends   = unserialize($entry['friends']);
      $ffriends[] = $uid;
      
      $sql = "UPDATE `scala_users` SET `friends` = '".serialize($ffriends)."' WHERE `id` = '".$send_id."'";
      $query = mysql_query($sql);
        
      echo "<div class=\"dialog\">Yeah! Du bist jetzt mit <b>$fnick</b> ($ffirstname $flastname) befreudet.</div>";
    } else {
      echo "Da hat irgendetwas nicht funktioniert&hellip; Wir sind untr&ouml;stlich!";
    }
  }
?>