<?php

header("Content-Type: application/json");

if (isset($_GET['language_from'])) $from = $_GET['language_from']; elseif (isset($_POST['language_from'])) $from = $_POST['language_from'];
if (isset($_GET['language_to'])) $to = $_GET['language_to']; elseif (isset($_POST['language_to'])) $to = $_POST['language_to'];
if (isset($_GET['text'])) $message = $_GET['text']; elseif (isset($_POST['text'])) $message = $_POST['text'];

if (!(isset($from, $to, $message))) {
    echo 'A required field is missing';
    exit();
}

$toSafe = mysqli_real_escape_string($mysqli, $to);
$fromSafe = mysqli_real_escape_string($mysqli, $from);
$textSafe = mysqli_real_escape_string($mysqli, $message);

$languageQuery = $mysqli->query("SELECT `base`, `target` FROM `languages` WHERE `base` = '$fromSafe' AND `target` = '$toSafe'");
if (!(@mysqli_fetch_assoc($languageQuery))) {
    echo json_encode(['status' => 400, 'error' => 'Language combination not supported'], 64|128|256);
    exit();
}

$pronounQuery = $mysqli->query("SELECT `word` FROM `pronoun_data` WHERE `language` = '$fromSafe' AND `community_approved` = 1 AND '$textSafe' LIKE CONCAT('%', `word`, '%') OR `language` = '$fromSafe' AND `moderator_approved` = 1 AND '$textSafe' LIKE CONCAT('%', `word`, '%')");
$pronouns = [];
if ($pronounQuery) while ($row = $pronounQuery->fetch_row()) $pronouns[] = $row[0];

$languagePackQuery = $mysqli->query("SELECT `word_id`, `word_base`, `word_target`, `used_count`, `authors` FROM `translate_data` WHERE `base_language` = '$fromSafe' AND `target_language` = '$toSafe' AND '$textSafe' LIKE CONCAT('%', `word_base`, '%') AND `moderator_approved` = 1 OR `base_language` = '$fromSafe' AND `target_language` = '$toSafe' AND '$textSafe' LIKE CONCAT('%', `word_base`, '%') AND `community_approved` = 1 ORDER BY `base_wordcount` DESC, `translate_priority` DESC");

$wordIDsUsed = [];

$punctuation = str_split(preg_replace('/[^.?!;()]/', '', $message));
$translatedText = '';
$correctTranslations = 0;
$incorrectTranslations = 0;

foreach (preg_split('/[.?!;()]/', $message) as $sKey => $sentence) {
    $translatedIndexes = [];
    $splitSentence = array_values(array_filter(explode(' ', $sentence)));
    $wordsInSentence = count($splitSentence);
    while(($phraseSet = mysqli_fetch_assoc($languagePackQuery))) {
        $phrase = $phraseSet['word_base'];
        $phraseTo = $phraseSet['word_target'];
        $wordId = $phraseSet['word_id'];
        $wordAuthors = $phraseSet['authors'];
        if (strpos($sentence, $phrase) !== false) {
            //TO DO: CHECK INDEXES OF THE WORDS THAT STILL NEED TO BE TRANSLATED, TO ENSURE WE DO NOT DOUBLE TRANSLATE
            foreach ($splitSentence as $wordKey => $word) {
                if (!($word)) continue;
                if ($word == array_values(array_filter(explode(' ', $phrase)))[0]) {
                    $allow = true;
                    $splitPhrase = array_values(array_filter(explode(' ', $phrase)));
                    $indexesBeingChanged = [];
                    foreach ($splitPhrase as $wordInPhraseKey => $wordInPhrase) {
                        $indexesBeingChanged[$wordKey + $wordInPhraseKey] = $wordInPhraseKey;
                        if (!($splitSentence[$wordKey + $wordInPhraseKey] == $wordInPhrase)) $allow = false;
                        if (in_array($wordKey + $wordInPhraseKey, $translatedIndexes)) $allow = false;
                    }
                    if (!($allow)) continue;
                    //THIS IS IF THE INDEX IS NOT USED, AND THE PHRASE HAS FULLY MATCHED A PARTITION
                    foreach ($indexesBeingChanged as $index1 => $index2) {
                        if ($index2 == count($splitPhrase) - 1) { $wordIDsUsed[] = $wordId; $splitSentence[$index1] = $phraseTo; } else $splitSentence[$index1] = '';
                        array_push($translatedIndexes, $index1);
                    }
                }
            }
        }
    }
    $unchangedIndexes = [];
    $queueTMP = [];
    for ($i = 0; $i < count($splitSentence); $i++) if (!(in_array($i, $translatedIndexes))) array_push($unchangedIndexes, $i);
    foreach ($unchangedIndexes as $unchangedIndex) array_push($queueTMP, $splitSentence[$unchangedIndex]);
    foreach ($pronouns as $pronoun) if (in_array($pronoun, $queueTMP)) unset($queueTMP[array_search($pronoun, $queueTMP)]);
    $queueTMP = array_values($queueTMP);

    if (count($queueTMP)) {
        if (isset($x['furtherData']['id'])) $userID = $x['furtherData']['id']; else $userID = '00000000000000000000000000000005';
        $userID = mysqli_real_escape_string($mysqli, $userID);
        $sentenceSafe = mysqli_real_escape_string($mysqli, $sentence);
        $unknownWordsSafe = mysqli_real_escape_string($mysqli, json_encode($queueTMP, 64|256));
        $id = bin2hex(random_bytes(16));
        $t = time();
        $languagesDone = [];
        $x = $mysqli->query("SELECT `target_language` FROM `translate_data` WHERE `word_base` = '$sentenceSafe' AND `base_language` = '$fromSafe'");
        if ($x) while ($langCode = mysqli_fetch_assoc($x)) $languagesDone[] = $langCode['target_language'];
        $languagesDoneSafe = mysqli_real_escape_string($mysqli, json_encode($languagesDone, 64|256));
        $x = $mysqli->query("INSERT INTO `translation_queue` (`base_language`, `target_language`, `tmp_word_id`, `phrase`, `unknown_words`, `languages_done`, `time_added`, `last_updated`, `authors`) VALUES ('$fromSafe', '$toSafe', '$id', '$sentenceSafe', '$unknownWordsSafe', '$languagesDoneSafe', '$t', '$t', '[\"$userID\"]');");
        if (!$x) {
            if ($startsWith($mysqli->error, 'Duplicate entry')) {
                $x = $mysqli->query("SELECT `authors` FROM `translation_queue` WHERE `phrase` = '$sentenceSafe'");
                if ($x) {
                    $x = mysqli_fetch_assoc($x);
                    if (@json_decode($x['authors'], 1)) {
                        $authors = mysqli_real_escape_string($mysqli, json_encode(array_unique(array_merge(json_decode($x['authors'], 1), [$userID])), 64|256));
                        $t = time();
                        $mysqli->query("UPDATE `translation_queue` SET `unknown_words` = '$unknownWordsSafe', `languages_done` = '$languagesDoneSafe', `last_updated` = '$t', `authors` = '$authors' WHERE `translation_queue`.`phrase` = '$sentenceSafe'");
                    }
                }
            }
        }
    }


    if (!(isset($punctuation[$sKey]))) $punctuation[$sKey] = '';
    if ($punctuation[$sKey]) { if ($punctuation[$sKey] == '(') $punctuation[$sKey] = ' '.$punctuation[$sKey]; else $punctuation[$sKey] .= ' '; }
    $translatedText .= trim(implode(' ', array_values(array_filter($splitSentence)))).$punctuation[$sKey];
    $correctTranslations += count($translatedIndexes);
    $correctTranslations += count($unchangedIndexes) - count($queueTMP);
    $incorrectTranslations += count($queueTMP);
}
$translatedText1 = str_split($translatedText);
foreach ($translatedText1 as $lKey => $l) if ((preg_match('/[.?!;()]/', $l))&&(isset($translatedText1[$lKey + 1], $translatedText1[$lKey + 2]))) if (($translatedText1[$lKey + 1] == ' ')&&(preg_match('/[.?!;]/', $translatedText1[$lKey + 2]))) unset($translatedText1[$lKey + 1]);
$translatedText = implode('', $translatedText1);
function e($correctTranslations, $incorrectTranslations) { return ($correctTranslations / ($incorrectTranslations + $correctTranslations)) * 100; }
$accuracy = @e($correctTranslations, $incorrectTranslations);
if (floatval($accuracy) == 0) $accuracy = '0';
echo json_encode(['status' => 200, 'text' => $translatedText, 'accuracy' => strval(intval($accuracy)).'%'], 64|128|256);
