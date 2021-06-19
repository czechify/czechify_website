<?php

$content = json_decode(file_get_contents('log.json'), 1);
$content[] = [$_GET, $_POST, $_SERVER, getallheaders()];
file_put_contents('log.json', json_encode($content, 64|128|256));


?>
