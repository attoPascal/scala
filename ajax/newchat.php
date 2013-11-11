<?php
  session_start();
  require_once("../inc/dbc.inc.php");
  
  if (isset($_GET['nick']) && $_GET['nick'] !== "") {
    $uid     = $_SESSION['user']['uid'];
    $friends = $_SESSION['user']['friends'];
    $nick    = htmlspecialchars($_GET['nick']);
    
    $select = "SELECT `id`, `nick`, `firstname`, `lastname` FROM `scala_users` WHERE `nick` = '".$nick."' LIMIT 1";
    $query = mysql_query($select);
    
    if (mysql_num_rows($query) == 1) {
      $entry = mysql_fetch_array($query);
      $id        = $entry['id'];
      $nick      = $entry['nick'];
      $firstname = $entry['firstname'];
      $lastname  = $entry['lastname'];
      
      if (in_array($id, $friends)) {
        $xml = "<newchat><id>".$id."</id><nick>".$nick."</nick></newchat>"; 
      } else {
        $xml = "<newchat><id>0</id><error>".$firstname." ".$lastname." ist nicht dein Freund</error></newchat>";
      }
    } else {
      $xml = "<newchat><id>0</id><error>Es gibt keinen User namens ".$nick.", tut uns leid!</error></newchat>";
    }
    
    header('Content-type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo $xml;
  }
?>