<?php

$socials = json_decode(file_get_contents($ref.'assets/json/social.json'), 1);

foreach ($socials as $social) {
    if ($social['type'] == 'discord') {
        header("Location: ".$social['url']);
        exit();
    }
}

?>
