<?php

header("Content-Type: application/json");

echo json_encode(array_reverse(json_decode(file_get_contents('apiAccessFile.json'), 1)), 64|128|256);


 ?>
