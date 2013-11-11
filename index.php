<?php
  error_reporting(E_ALL ^ E_NOTICE);
  session_start();
  require_once("inc/dbc.inc.php");
  
  if (isset($_SESSION['user'])) {
    $page = "home";
  } else {
    $page = "start";
  }
  
  if ($_GET['action'] == "logout") {
    setcookie("PHPSESSID", "", time()-3600);
    $_SESSION = array();   
    $page = "start";
  } 
?>
<!DOCTYPE html>

<html>
<head>
  <title>scala &beta;&epsilon;&tau;&alpha;</title>
  <meta name="description" content="scala - Spezialgebiet &quot;Ajax&quot; f&uuml;r die m&uuml;ndliche Informatik-Matura. Pascal C. Attwenger, 2011" />
  <meta name="author" content="Pascal C. Attwenger" />
  <meta name="keywords" content="scala, Ajax, Matura, Spezialgebiet, JavaScript, jQuery" />
  <meta name="date" content="2011-01-09" />
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="styles.css" />
  <link rel="stylesheet" type="text/css" href="ui/css/blitzer2/jquery-ui-1.8.10.custom.css" />
  <script type="text/javascript" src="jquery.js"></script>
  <script type="text/javascript" src="ui/js/jquery-ui-1.8.10.custom.min.js"></script>
  <script type="text/javascript" src="ui/js/jquery.ui.datepicker-de.js"></script>
  <script type="text/javascript" src="custom.js"></script>
</head>

<body>
<?php
  if ($page == "home") {
    include('inc/main.inc.php');
  } else {
    include('inc/start.inc.php');
  }
?>
</body>
</html>