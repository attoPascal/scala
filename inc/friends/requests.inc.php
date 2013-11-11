<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  require_once("../dbc.inc.php");

  $uid = $_SESSION['user']['uid'];
  
  echo "<h1>Freundschaftsanfragen</h1>\n";

  echo "<h2>Eingang:</h2>\n";
  echo "<div id=\"inbox\">\n";
  
  $sql = 
    "SELECT r.`id`, r.`send_id`, r.`sent_date`, u.`nick`, u.`firstname`, u.`lastname`
    FROM `scala_users` AS u, `scala_reqs` AS r
    WHERE `rec_id` = '".$uid."' AND `accepted` = '0' AND r.`send_id` = u.`id`
    ORDER BY `sent_date` ASC";
  $query = mysql_query($sql);
  
  if (mysql_num_rows($query) > 0) {  
    while ($entry = mysql_fetch_array($query)) {
      $req_id    = $entry['id'];
      $send_id   = $entry['send_id'];
      $sent_date = $entry['sent_date'];
      $nick      = $entry['nick'];
      $firstname = $entry['firstname'];
      $lastname  = $entry['lastname'];
        
      echo "<div class=\"friend\">\n";
      echo "  <div class=\"floatbuttons\"><a href=\"?page=friends&action=accept&req_id=".$req_id."\" class=\"button accept\">Akzepieren</a><a href=\"?page=friends&action=decline&req_id=".$req_id."\" class=\"button decline\">Ablehnen</a></div>\n";
      echo "  <div class=\"nick\"><a href=\"?page=profile&id=".$rec_id."\" class=\"ajax\">".$nick."</a></div>\n";
      echo "  <div>".$firstname." ".$lastname."</div>\n";
      //echo "  <div>versendet: ".$sent_date."</div>\n";
      echo "</div>\n";
    }
  } else {
    echo "<p>Du hast zur Zeit keine unbeantworteten Anfragen im Eingang</p>\n";
  }  

  echo "</div>\n";
  echo "<h2><br />Ausgang:</h2>\n";
  echo "<div id=\"outbox\">\n";

  $sql = 
    "SELECT r.`id`, r.`rec_id`, r.`sent_date`, u.`nick`, u.`firstname`, u.`lastname`
    FROM `scala_users` AS u, `scala_reqs` AS r
    WHERE `send_id` = '".$uid."' AND `accepted` = '0' AND r.`rec_id` = u.`id`
    ORDER BY `sent_date` ASC";
  $query = mysql_query($sql);
  
  if (mysql_num_rows($query) > 0) {  
    while ($entry = mysql_fetch_array($query)) {
      $req_id    = $entry['id'];
      $rec_id    = $entry['rec_id'];
      $sent_date = $entry['sent_date'];
      $nick      = $entry['nick'];
      $firstname = $entry['firstname'];
      $lastname  = $entry['lastname'];
        
      echo "<div class=\"friend\">\n";
      echo "  <div class=\"floatbuttons\"><a href=\"?page=friends&action=retract&req_id=".$req_id."\" class=\"button retract\">Anfrage zur&uuml;ckziehen</a></div>\n";
      echo "  <div class=\"nick\"><a href=\"?page=profile&id=".$rec_id."\">".$nick."</a></div>\n";
      echo "  <div>".$firstname." ".$lastname."</div>\n";
      //echo "  <div>versendet: ".$sent_date."</div>\n";
      echo "</div>\n";
    }
  } else {
    echo "<p>Du hast zur Zeit keine unbeantworteten Anfragen im Ausgang</p>\n";
  }
  
  echo "</div>\n";
?>