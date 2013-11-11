<?php
  if (!isset($_SESSION)) {
    session_start();
  }
  
  $err_l = "";
  $err_s = "";
  
  $nick_l_val    = "";
  $sex_val       = $_SESSION['saved']['sex'];
  $firstname_val = $_SESSION['saved']['firstname'];
  $lastname_val  = $_SESSION['saved']['lastname'];
  $birthday_val  = $_SESSION['saved']['birthday'];
  $nick_s_val    = $_SESSION['saved']['nick_s'];
  
  $_SESSION['saved'] = array();
  
  //Login-Errors
  if ($_SESSION['errors']['nick_l']) {
    $err_l .= "<div>Username existiert nicht</div>\n";
    $_SESSION['errors']['nick_l'] = false;
  }
  if ($_SESSION['errors']['pass_l']) {
    $err_l .= "<div>Falsches Passwort eingegeben</div>\n";
    $_SESSION['errors']['pass_l'] = false;
    $nick_l_val = $_SESSION['saved']['nick'];
  }
  
  //Signup-Errors
  if ($_SESSION['errors']['sex']) {
    $err_s .= "<div>Bitte w&auml;hle dein Geschlecht aus! (im Zweifelsfall nachschauen&hellip;)</div>\n";
    $_SESSION['errors']['sex'] = false;
  }
  if ($_SESSION['errors']['firstname']) {
    $err_s .= "<div>Bitte gib deinen Vornamen ein!</div>\n";
    $_SESSION['errors']['firstname'] = false;
  }
  if ($_SESSION['errors']['lastname']) {
    $err_s .= "<div>Bitte gib deinen Nachnamen ein!</div>\n";
    $_SESSION['errors']['lastname'] = false;
  }
  if ($_SESSION['errors']['birthday']) {
    $err_s .= "<div>Bitte w&auml;hle dein Geburtsdatum aus!</div>\n";
    $_SESSION['errors']['birthday'] = false;
  }
  if ($_SESSION['errors']['nick_s']['none']) {
    $err_s .= "<div>Bitte gib einen Username ein!</div>\n";
    $_SESSION['errors']['nick_s']['none'] = false;
  }
  if ($_SESSION['errors']['nick_s']['taken']) {
    $err_s .= "<div>Dein eingegebener Username ist schon besetzt. Bitte such dir einen anderen aus!</div>\n";
    $_SESSION['errors']['nick_s']['taken'] = false;
  }
  if ($_SESSION['errors']['pass1']['none']) {
    $err_s .= "<div>Bitte gib ein Passwort ein!</div>\n";
    $_SESSION['errors']['pass1']['none'] = false;
  }
  if ($_SESSION['errors']['pass1']['short']) {
    $err_s .= "<div>Dein eingegebenes Passwort ist zu kurz. Bitte gib ein l&auml;ngeres ein!</div>\n";
    $_SESSION['errors']['nick_s'] = false;
  }
  if ($_SESSION['errors']['pass2']['none']) {
    $err_s .= "<div>Bitte gib dein Passwort ein zweites Mal ein!</div>\n";
    $_SESSION['errors']['pass2']['none'] = false;
  }
  if ($_SESSION['errors']['pass2']['diff']) {
    $err_s .= "<div>Deine eingegebenen Passw&ouml;rter stimmen nicht &uuml;berein. Bitte gib sie noch einmal ein</div>\n";
    $_SESSION['errors']['pass2']['diff'] = false;
  }
?>
<div id="start">
  <h1>Willkommen bei <span class="logo">scala<span>&beta;&epsilon;&tau;&alpha;</span></span>!</h1>

  <div id="tabs">
    <ul>
      <li><a href="#login">Login</a></li>
      <li><a href="#signup">Registrieren</a></li>
    </ul>
    
    <div id="login">
      <?php echo ($err_l !== "") ? "<div class=\"errors\">".$err_l."</div>\n" : "" ?>
      <form action="redirect.php" method="post">
        <table>
          <tr>
            <td>Username:</td>
            <td><input type="text" name="nick" value="<?php echo $nick_l_val ?>" /></td>
          </tr>
          <tr>
            <td>Passwort:</td>
            <td><input type="password" name="pass" value="" /></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" name="login" value="Login" /></td>
          </tr>
        </table>
      </form>
    </div>
    
    <div id="signup">
      <?php echo ($err_s !== "") ? "<div class=\"errors\">".$err_s."</div>\n" : "" ?>
      <form action="redirect.php" method="post">
        <table>
          <tr>
            <td>Geschlecht:</td>
            <td>
              <input type="radio" name="sex" value="m" id="sexm" <?php echo ($sex_val == "m") ? "checked=\"checked\"" : "" ?> /><label for="sexm">&#9794;</label>
              <input type="radio" name="sex" value="f" id="sexf" <?php echo ($sex_val == "f") ? "checked=\"checked\"" : "" ?> /><label for="sexf">&#9792;</label>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Vorname:</td>
            <td><input type="text" name="firstname" id="firstname" value="<?php echo $firstname_val ?>" /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Nachname:</td>
            <td><input type="text" name="lastname" id="lastname" value="<?php echo $lastname_val ?>" /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Geburtsdatum:</td>
            <td><input type="text" name="birthday" id="birthday" value="<?php echo $birthday_val ?>" /></td>
            <td>(JJJJ-MM-TT)</td>
          </tr>
          <tr>
            <td>Nickname:</td>
            <td><input type="text" name="nick" id="nick" value="<?php echo $nick_s_val ?>" /></td>
            <td id="checknick">&nbsp;</td>
          </tr>
          <tr>
            <td>Passwort:</td>
            <td><input type="password" name="pass1" id="pass1" value="" /></td>
            <td id="checkpass1">&nbsp;</td>
          </tr>
          <tr>
            <td>Passwort erneut:</td>
            <td><input type="password" name="pass2" id="pass2" value="" /></td>
            <td id="checkpass2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><input type="submit" name="signup" value="Registrieren" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>