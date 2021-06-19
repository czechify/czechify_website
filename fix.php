<?php

$swedifyWords = json_decode(file_get_contents('../swedify/api/words/allWords.json'), 1);
$czechifyWords = json_decode(file_get_contents('api/words/allWords.json'), 1);

foreach ($swedifyWords as $k => $v) {
    if (($v['wordSwedish'] == $czechifyWords[$k]['wordCzech'])) $swedifyWords[$k]['wordSwedish'] = '';
}

file_put_contents('../swedify/api/words/allWords.json', json_encode($swedifyWords, 128));


echo 'all done';


 ?>
