<?php

$text = $_POST["text"];

$file = fopen("/tmp/temp.txt", "w") or die("Datei konnte nicht geöffnet werden.");
fwrite($file, $text) or die("Die Eingabe ist leer.");
fclose($file) or die("Datei konnte nicht geschlossen werden.");
unlink($file_pointer);

$output = shell_exec('python3 ../../main.py dn /tmp/temp.txt') or die("Dekomprimierungsfehler");

echo $output;