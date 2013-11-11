<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if (isset($_POST['req_id']) && $_POST['req_id'] !== "") {
    $req_id  = $_POST['req_id'];
    
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
      
      //Anfrage löschen
      $sql = "DELETE FROM `scala_reqs` WHERE `id` = '".$req_id."' LIMIT 1";
      $query = mysql_query($sql);
        
      echo "<div class=\"dialog\">Du hast die Freundschaftsanfrage von <b>$fnick</b> ($ffirstname $flastname) abgelehnt.</div>";
    }
  } else {
    echo "<div>Unknown Error. Konnte Anfrage nicht l&ouml;schen.</div>";
  }
?>