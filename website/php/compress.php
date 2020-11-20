<?php

$text = $_POST["text"];

$file = fopen("../../texts/temp.txt", "w") or die("Datei konnte nicht geöffnet werden.");
fwrite($file, $text) or die("Datei konnte nicht beschrieben werden.");
fclose($file) or die("Datei konnte nicht geschlossen werden.");

$output = shell_exec('py ../../main.py cn ../../texts/temp.txt') or die("Komprimierungsfehler");

echo $output;