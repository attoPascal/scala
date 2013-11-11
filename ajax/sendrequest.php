<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if (isset($_POST['fid']) && $_POST['fid'] !== "") {
    $uid = $_SESSION['user']['uid'];
    $fid = $_POST['fid'];
    
    $sql = "INSERT INTO `scala_reqs` (
      `id`,
      `send_id`,
      `rec_id`,
      `text`,
      `accepted`,
      `sent_date`,
      `acc_date`
    ) VALUES (
      NULL, '".$uid."', '".$fid."', '', '0', NULL, ''
    );";
    $query = mysql_query($sql);
  }
?>