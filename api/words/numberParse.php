<?php
/*
$db = json_decode(file_get_contents("./allWords.json"), 1);

foreach ($db as $key => $item) if (!(gettype($item['value']) == "integer")) $db[$key]['value'] = json_decode($db[$key]['value']);

$myfile = fopen("./allWords.json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($db, 128));
fclose($myfile);

echo "done";
*/
?>