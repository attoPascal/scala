<?php
  require_once("../inc/dbc.inc.php");
  
  if (isset($_GET['nick']) && $_GET['nick'] !== "") {
    $nick = $_GET['nick'];
    
    $select = "SELECT * FROM `scala_users` WHERE `nick` = '".$nick."' LIMIT 1";
    $result = mysql_query($select);
    
    if (mysql_num_rows($result) == 0) {
      echo "<span style=\"color: green;\">Nickname frei!</span>";
    } else {
      echo "<span style=\"color: red;\">Nickname nicht frei</span>";
    }
  }
?>