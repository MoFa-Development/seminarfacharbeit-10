<?php

$text = $_POST["text"];

$file = fopen("/tmp/temp.txt", "w") or die("Datei konnte nicht geöffnet werden.");
fwrite($file, $text) or die("Die Eingabe ist leer.");
fclose($file) or die("Datei konnte nicht geschlossen werden.");

$output = var_dump(shell_exec('py ../../main.py cn /tmp/temp.txt'));

echo $output;