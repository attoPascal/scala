<?php
  $db_connect = @mysql_connect('a1200595.mysql.univie.ac.at', 'a1200595', '1SecurePW')
    or die("<div><b>ERROR:</b> Verbindung zur Datenbank fehlgeschlagen</div>");  
  $db_select = @mysql_select_db('a1200595')
    or die("<div><b>ERROR:</b> Auswahl der Datenbank fehlgeschlagen</div>");
?>