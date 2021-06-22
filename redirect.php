<?php

if (!(substr($_SERVER['REQUEST_URI'], strlen($_SERVER['REQUEST_URI']) - 1, 1) == '/')) header("Location: $_SERVER[REQUEST_URI]/");

$arr = array_slice(explode('/', substr(explode('?', $_SERVER['REQUEST_URI'])[0], strlen(dirname($_SERVER['PHP_SELF'])))), 0, -1);

$p = str_repeat('../', count($arr));

$x = $p.'./#/'.implode('/', $arr);

//var_dump($x);

//$arr = array_values(array_filter(explode('/', str_replace(dirname($_SERVER['PHP_SELF']), '', $_SERVER['REQUEST_URI'])), 'strlen'));

$postsData = json_decode(file_get_contents('./assets/json/posts.json'), 1);

if ($arr[0] == 'posts') {
    $x = str_replace('#/posts/', '#/home/posts/', $x);
    if ((count($arr) > 1)&&(isset($postsData[$arr[1]]))) {
        echo '<title>'.$postsData[$arr[1]]['name'].' - '.$postsData[$arr[1]]['type'].' | Czechify</title>';
        echo '<meta name="description" content="';
        foreach (explode(' ', $postsData[$arr[1]]['text']) as $k => $w) if ($k <= 16) { if ($k == 16) echo str_replace(['.', ',', '!', '?'], '', $w); else echo $w.' '; } else { echo '...'; break; }
        echo '">';
    }
}elseif ($arr[0] == 'about') {
    echo '<title>About Us | Czechify</title>';
}
//exit();
?><script>window.location.href = '<?php echo $x; ?>';</script>
