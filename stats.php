<?php

$data = $unapproved = $mod_approved = $community_approved = $game_approved = $meta = [];

$query = $mysqli->query("SELECT `base_language`, `target_language`, `moderator_approved`, `community_approved`, `game_approved` FROM `translate_data`");

if ($query) while ($row = mysqli_fetch_assoc($query)) {
    if ($row['moderator_approved']) {
        if ($row['game_approved']) {
            if (!(isset($game_approved[$row['base_language'].' => '.$row['target_language']]))) $game_approved[$row['base_language'].' => '.$row['target_language']] = 0;
            $game_approved[$row['base_language'].' => '.$row['target_language']] ++;
        }
        if (!(isset($mod_approved[$row['base_language'].' => '.$row['target_language']]))) $mod_approved[$row['base_language'].' => '.$row['target_language']] = 0;
        $mod_approved[$row['base_language'].' => '.$row['target_language']] ++;
    } elseif ($row['community_approved']) {
        if ($row['game_approved']) {
            if (!(isset($game_approved[$row['base_language'].' => '.$row['target_language']]))) $game_approved[$row['base_language'].' => '.$row['target_language']] = 0;
            $game_approved[$row['base_language'].' => '.$row['target_language']] ++;
        }
        if (!(isset($community_approved[$row['base_language'].' => '.$row['target_language']]))) $community_approved[$row['base_language'].' => '.$row['target_language']] = 0;
        $community_approved[$row['base_language'].' => '.$row['target_language']] ++;
    } else {
        if (!(isset($unapproved[$row['base_language'].' => '.$row['target_language']]))) $unapproved[$row['base_language'].' => '.$row['target_language']] = 0;
        $unapproved[$row['base_language'].' => '.$row['target_language']] ++;
    }
    if (!(isset($data[$row['base_language'].' => '.$row['target_language']]))) $data[$row['base_language'].' => '.$row['target_language']] = 0;
    $data[$row['base_language'].' => '.$row['target_language']] ++;
}

$query = $mysqli->query('SELECT `language_code`, `text` FROM `language_meta`');
if ($query) while ($row = mysqli_fetch_assoc($query)) if (!(isset($meta[$row['language_code']]))) $meta[$row['language_code']] = $row['text'];

echo '<table style="border: 1px solid black; border-bottom: 0;"><tr><th style="border-bottom: 1px solid black;"></th><th style="border-left: 1px solid black;border-bottom: 1px solid black;">All</th><th style="border-left: 1px solid black;border-bottom: 1px solid black;">Community Approved</th><th style="border-left: 1px solid black;border-bottom: 1px solid black;">Moderator Approved</th><th style="border-left: 1px solid black;border-bottom: 1px solid black;">Game Approved</th><th style="border-left: 1px solid black;border-bottom: 1px solid black;">Not Approved</th>';

foreach ($meta as $a1 => $a2) foreach ($meta as $b1 => $b2) {
    echo '<tr><td style="border-bottom: 1px solid black;">'.$a2.' => '.$b2.'</td><td style="border-left: 1px solid black;border-bottom: 1px solid black;text-align: center;">';
    if (isset($data[$a2.' => '.$b2])) echo $data[$a2.' => '.$b2]; else echo '0';
    echo '</td><td style="border-left: 1px solid black;border-bottom: 1px solid black;text-align: center;">';
    if (isset($community_approved[$a1.' => '.$b1])) echo $community_approved[$a1.' => '.$b1]; else echo '0';
    echo '</td><td style="border-left: 1px solid black;border-bottom: 1px solid black;text-align: center;">';
    if (isset($mod_approved[$a1.' => '.$b1])) echo $mod_approved[$a1.' => '.$b1]; else echo '0';
    echo '</td><td style="border-left: 1px solid black;border-bottom: 1px solid black;text-align: center;">';
    if (isset($game_approved[$a1.' => '.$b1])) echo $game_approved[$a1.' => '.$b1]; else echo '0';
    echo '</td><td style="border-left: 1px solid black;border-bottom: 1px solid black;text-align: center;">';
    if (isset($unapproved[$a1.' => '.$b1])) echo $unapproved[$a1.' => '.$b1]; else echo '0';
    echo '</td></tr>';
}

echo '</table>';

?>
