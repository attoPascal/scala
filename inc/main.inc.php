<?php
  session_start();
  $nick = $_SESSION['user']['nick'];
?>
<div id="all">
<div id="header">
  <div id="plink">Ich: <a href="?page=profile" class="ajax"><?php echo $nick ?></a></div>
  <div id="logo"><a href="?page=home" class="ajax">scala<span>&beta;&epsilon;&tau;&alpha;</span></a></div>
</div>
<div id="nav">
  <div id="accordion">
    <h3><a href="#">Wir</a></h3>
    <div>
      <a href="?page=home" class="ajax">Feed</a><br />
      <a href="?page=friends" class="ajax">Freunde</a><br />
      <a href="?page=chat" class="ajax">Chat</a>
    </div>
    <h3><a href="#">Ich</a></h3>
    <div>
      <a href="?page=profile" class="ajax">Mein Profil</a><br />
      <a href="?page=settings" class="ajax">Einstellungen</a><br />
      <a href="?action=logout">Logout</a>
    </div>
    <h3><a href="#">Es</a></h3>
    <div>
      <a href="?page=aboutscala" class="ajax">&Uuml;ber das Projekt</a><br />
      <a href="?page=aboutpascal" class="ajax">&Uuml;ber den Autor</a><br />
      <a href="?page=impressum" class="ajax">&Uuml;ber Juristisches</a>
    </div>
  </div>
</div>

<div id="content"><div>
<?php
  $page = isset($_GET['page']) ? $_GET['page'] : "home";
  
  switch ($page) {
  case "home":
    include('inc/home.inc.php');
    break;
  case "friends":
    include('inc/friends.inc.php');
    break;
  case "chat":
    include('inc/chat.inc.php');
    break;
 case "profile":
    include('inc/profile.inc.php');
    break;
  case "settings":
    include('inc/settings.inc.php');
    break;
  case "aboutscala":
    include('inc/aboutscala.inc.php');
    break;
  case "aboutpascal":
    include('inc/aboutpascal.inc.php');
    break;
  case "impressum":
    include('inc/impressum.inc.php');
    break;
  default:
    include('inc/home.inc.php');
  }
?>
</div></div>
<div style="clear:left" />
</div>