<?php
  $db_connect = @mysql_connect('REDACTED', 'REDACTED', 'REDACTED')
    or die("<div><b>ERROR:</b> Verbindung zur Datenbank fehlgeschlagen</div>");  
  $db_select = @mysql_select_db('REDACTED')
    or die("<div><b>ERROR:</b> Auswahl der Datenbank fehlgeschlagen</div>");
?>
