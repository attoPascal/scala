<?php
  session_start();
  require_once("inc/dbc.inc.php");
  
  if (isset($_POST['login'])) {
    $nick = $_POST['nick'];
    $pass = $_POST['pass'];
    
    $sql   = "SELECT * FROM `scala_users` WHERE `nick` = '".$nick."' LIMIT 1";
    $query = mysql_query($sql);
    $entry = mysql_fetch_array($query);
    
    if (mysql_num_rows($query) == 0) { //Username existiert nicht
      $_SESSION['errors']['nick_l'] = true;
      $redirect = "login";
    } elseif ($entry['pass'] !== md5($pass)) { //Passwort falsch
      $_SESSION['errors']['pass_l'] = true;
      $_SESSION['saved']['nick_l'] = $nick;
      $redirect = "login";
    } else { //Passwort korrekt      
      $_SESSION['user']['nick']      = $nick;
      $_SESSION['user']['uid']       = $entry['id'];
      $_SESSION['user']['firstname'] = $entry['firstname'];
      $_SESSION['user']['lastname']  = $entry['lastname'];
      $_SESSION['user']['friends']   = $entry['friends'] ? unserialize($entry['friends']) : array();
      
      $_SESSION['errors'] = array();
      $_SESSION['saved'] = array();
      $redirect = "home";
    }
  } elseif (isset($_POST['signup'])) {
    $_SESSION['saved']['sex']       = $sex       = htmlspecialchars($_POST['sex']);
    $_SESSION['saved']['firstname'] = $firstname = htmlspecialchars($_POST['firstname']);
    $_SESSION['saved']['lastname']  = $lastname  = htmlspecialchars($_POST['lastname']);
    $_SESSION['saved']['birthday']  = $birthday  = htmlspecialchars($_POST['birthday']);
    $_SESSION['saved']['nick_s']    = $nick      = htmlspecialchars($_POST['nick']);
    $pass1 = htmlspecialchars($_POST['pass1']);
    $pass2 = htmlspecialchars($_POST['pass2']);
    
    $okay = true;
    
    $sql = "SELECT `id` FROM `scala_users` WHERE `nick` = '".$nick."' LIMIT 1";
    $query = mysql_query($sql);
    
    if ($nick == "") {
      $okay = false;
      $_SESSION['errors']['nick_s']['none'] = true;
    } elseif (mysql_num_rows($query) > 0) {
      $okay = false;
      $_SESSION['errors']['nick_s']['taken'] = true;
      $_SESSION['saved']['nick'] = "";
    }
    
    if ($pass1 == "") {
      $okay = false;
      $_SESSION['errors']['pass1']['none'] = true;;
    } elseif (strlen($pass1) <= 4) {
      $okay = false;
      $_SESSION['errors']['pass1']['short'] = true;
    }
    
    if ($pass2 == "") {
      $okay = false;
      $_SESSION['errors']['pass2']['none'] = true;;
    } elseif ($pass1 !== $pass2) {
      $okay = false;
      $_SESSION['errors']['pass2']['diff'] = true;
    }
    
    if ($sex == "") {
      $okay = false;
      $_SESSION['errors']['sex'] = true;
    }
    if ($firstname == "") {
      $okay = false;
      $_SESSION['errors']['firstname'] = true;
    }
    if ($lastname == "") {
      $okay = false;
      $_SESSION['errors']['lastname'] = true;
    }
    if ($birthday == "") {
      $okay = false;
      $_SESSION['errors']['birthday'] = true;
    }
    
    if ($okay) {
      $sql =
        "INSERT INTO `scala_users` (
          `id`,
          `nick`,
          `firstname`,
          `lastname`,
          `sex`,
          `birthday`,
          `pass`,
          `friends`
        )
        VALUES (
          NULL, '".$nick."', '".$firstname."', '".$lastname."', '".$sex."', '".$birthday."', MD5('".$pass1."') , '".serialize(array())."'
        );";
      $query = mysql_query($sql);
      
      $_SESSION['user']['uid']       = mysql_insert_id();
      $_SESSION['user']['nick']      = $nick;
      $_SESSION['user']['firstname'] = $firstname;
      $_SESSION['user']['lastname']  = $lastname;
      $_SESSION['user']['friends']   = array();
    
      $_SESSION['errors'] = array();
      $_SESSION['saved'] = array();
      $redirect = "home";
    } else {
      $redirect = "signup";
    }
  } else {
    $redirect = "login";
  }
  
  switch ($redirect) {
  case "home":
    $url  = "http://www.unet.univie.ac.at/~a1200595/scala/";
    $text = "Deine Anmeldung war erfolgreich!<br />Du wirst weitergeleitet in <span class=\"countdown\">3</span> Sekunden.";
    break;
  case "login":
    $url  = "http://www.unet.univie.ac.at/~a1200595/scala/index.php#login";
    $text = "Dein Login war nicht erfolgreich.<br />Du wirst zurückgeleitet in <span class=\"countdown\">3</span> Sekunden.";
    break;
  case "signup":
    $url  = "http://www.unet.univie.ac.at/~a1200595/scala/index.php#signup";
    $text = "Deine Registrierung war nicht erfolgreich.<br />Du wirst zurückgeleitet in <span class=\"countdown\">3</span> Sekunden.";
    break;
  }
?>
<!DOCTYPE html>

<html>
<head>
  <title>scala &beta;&epsilon;&tau;&alpha; - Redirecting&hellip;</title>
  <meta http-equiv="refresh" content="3; URL=<?php echo $url ?>">
  <meta name="description" content="scala - Spezialgebiet &quot;Ajax&quot; f&uuml;r die m&uuml;ndliche Informatik-Matura. Pascal C. Attwenger, 2011" />
  <meta name="author" content="Pascal C. Attwenger" />
  <meta name="keywords" content="scala, Ajax, Matura, Spezialgebiet, JavaScript, jQuery" />
  <meta name="date" content="2011-01-09" />
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>
  <div id="redirect">
    <h1>Weiterleitung&hellip;</h1>
    <div><?php echo $text ?><br /></div>
    <div>(Wenn nicht, bitte <a href="<?php echo $url ?>">hier klicken</a>!)</div>
  </div>
</body>
</html>