<?php

/*

$x = json_decode(file_get_contents('./translate/pronoun_dictionaries/EN-pronouns.json'), 1);

foreach ($x as $e) {
    $id = bin2hex(random_bytes(16));
    $t = time();
    echo "INSERT INTO `pronoun_data` (`language`, `word_id`, `word`, `upvotes`, `upvoted_by`, `downvotes`, `downvoted_by`, `community_approved`, `moderator_approved`, `game_approved`, `value`, `time_added`, `time_approved`, `deletion_requested`, `used_count`, `authors`, `word_type`, `definitions`) VALUES ('EN_GB', '$id', '$e', '0', '{}', '0', '{}', '0', '1', '0', '1', '$t', '$t', '0', '0', '{\"Martin\"}', NULL, '{}');";
}

*/
/*

$x = json_decode(file_get_contents('./translate/language_packs/CS-EN.json'), 1);

foreach ($x as $k => $word) {
    $wordCount = count(array_values(explode(' ', trim($k))));
    $id = bin2hex(random_bytes(16));
    $t = time();
    echo "INSERT INTO `translate_data` (`base_language`, `target_language`, `word_id`, `word_base`, `word_target`, `base_wordcount`, `upvotes`, `upvoted_by`, `downvotes`, `downvoted_by`, `community_approved`, `moderator_approved`, `game_approved`, `translate_priority`, `value`, `time_added`, `time_approved`, `deletion_requested`, `used_count`, `authors`) VALUES ('CS_CZ', 'EN_GB', '$id', '$k', '$word', '$wordCount', '0', '{}', '0', '{}', '0', '1', '0', '0', '1', '$t', '$t', '0', '0', '[\"Martin\"]');";
    //echo "INSERT INTO `tmp1` (`word_id`, `word_base`, `word_target`, `base_wordcount`, `upvotes`, `upvoted_by`, `downvotes`, `downvoted_by`, `community_approved`, `moderator_approved`, `game_approved`, `translate_priority`, `value`, `time_added`, `time_approved`, `deletion_requested`, `used_count`, `authors`) VALUES ('$id', '$k', '$t', '$wordCount', '0', '{}', '0', '{}', '0', '1', '0', '0', '1', '$t', '$t', '0', '0', '[\"Martin\"]');";
}

*/


$x = json_decode(file_get_contents('../swedify/api/words/allWords.json'), 1);

//var_dump($x);

foreach ($x as $word) {
    $id = mysqli_real_escape_string($mysqli, bin2hex(random_bytes(16)));
    $en = mysqli_real_escape_string($mysqli, $word['wordEnglish']);
    $sv = mysqli_real_escape_string($mysqli, $word['wordSwedish']);
    $wordCount = mysqli_real_escape_string($mysqli, count(array_values(explode(' ', trim($en)))));
    $val = mysqli_real_escape_string($mysqli, $word['value']);
    $t = mysqli_real_escape_string($mysqli, time());
    echo "INSERT INTO `translate_data` (`base_language`, `target_language`, `word_id`, `word_base`, `word_target`, `base_wordcount`, `upvotes`, `upvoted_by`, `downvotes`, `downvoted_by`, `community_approved`, `moderator_approved`, `game_approved`, `translate_priority`, `value`, `time_added`, `time_approved`, `deletion_requested`, `used_count`, `authors`) VALUES ('EN_GB', 'SV_SE', '$id', '$en', '$sv', '$wordCount', '0', '{}', '0', '{}', '0', '1', '1', '0', '$val', '$t', '$t', '0', '0', '[\"MiniKlick\"]');";
    //echo "INSERT INTO `tmp1` (`word_id`, `word_base`, `word_target`, `base_wordcount`, `upvotes`, `upvoted_by`, `downvotes`, `downvoted_by`, `community_approved`, `moderator_approved`, `game_approved`, `translate_priority`, `value`, `time_added`, `time_approved`, `deletion_requested`, `used_count`, `authors`) VALUES ('$id', '$en', '$cs', '$wordCount', '0', '{}', '0', '{}', '0', '1', '1', '0', '$val', '$t', '$t', '0', '0', '[\"Plankto\"]');";
    //echo "INSERT INTO `CS_CZ:EN_GB` (`word_id`, `word_base`, `word_target`, `base_wordcount`, `upvotes`, `upvoted_by`, `community_approved`, `moderator_approved`, `translate_priority`, `value`) VALUES ('".bin2hex(random_bytes(16))."', '$cs', '$en', '$wordCount', '0', '{}', '0', '0', '0', '1');";
}




//$x = json_decode(file_get_contents('./translate/translation_queues/EN-CS.json'), 1);

//foreach ($x as $k => $v) {
    //echo "INSERT INTO `translation_queue` (`base_language`, `target_language`, `tmp_word_id`, `phrase`, `unknown_words`, `languages_done`, `time_added`, `last_updated`, `authors`) VALUES ('', '', '', '', '', '[]', '$t', '$t', '[\"00000000000000000000000000000005\"]')";
    //var_dump('https://translate.czechify.com/api/?language_from=CS_CZ&language_to=EN_GB&text='.urlencode($v[0]));
    //var_dump(json_decode(file_get_contents('https://translate.czechify.com/api/?language_from=EN_GB&language_to=CS_CZ&text='.urlencode($v[0]))));
//}


//$langs = ['CS_CZ', 'EN_GB', 'DE_DE', 'IT_IT', 'RU_RU', 'ES_ES', 'SV_SE', 'PL_PL', 'TR_TR', 'FR_FR', 'PT_PT', 'PT_BR', 'KO_KP', 'KO_KR', 'JA_JP', 'ZH_CN', 'ZH_TW', 'HI_IN', 'NE_NP', 'HI_NP', 'FA_AF', 'FA_IR', 'UR_IN', 'UR_PK', 'AR_AE', 'DE_LU', 'DE_AT', 'DE_CH', 'DE_BE', 'NL_NL', 'SK_SK'];

//$langName = ['Czech', 'English', 'German', 'Italian', 'Russian', 'Spanish', 'Swedish', 'Polish', 'Turkey', 'French', 'Portugese', 'Portugese (Brazil)', 'Korean (North Korea)', 'Korean (South Korea)', 'Japanese', 'Chinese', 'Chinese (Taiwan)', 'Hindi (India)', 'Nepali', 'Hindi (Nepal)', 'Persian (Afghanistan)', 'Persian (Iran)', 'Urdu (India)', 'Urdu (Pakistan)', 'Arabic', 'German (Luxembourg)', 'German (Austria)', 'German (Switzerland)', 'German (Belgium)', 'Dutch', 'Slovak'];

//foreach ($langs as $e) foreach ($langs as $f) if (!($f == $e)) echo "CREATE TABLE IF NOT EXISTS `$e:$f` LIKE `CS_CZ:EN_GB`;";

//foreach ($langs as $i => $l) {
//    $l1 = $langName[$i];
//    echo "INSERT INTO `language_meta` (`language_code`, `text`) VALUES ('$l', '$l1');";
//}



//$stuff = file_get_contents('../martin/output.txt');
//$d = [];
//foreach (explode("\n", $stuff) as $data) {
//    if (!(isset($d[$data]))) $d[$data] = 0;
//    $d[$data]++;
//}
//var_dump($d);
