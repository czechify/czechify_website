<?php

if (isset($_GET['language_from'])) $from = $_GET['language_from']; elseif (isset($_POST['language_from'])) $from = $_POST['language_from'];
if (isset($_GET['language_to'])) $to = $_GET['language_to']; elseif (isset($_POST['language_to'])) $to = $_POST['language_to'];
if (isset($_GET['text'])) $message = $_GET['text']; elseif (isset($_POST['text'])) $message = $_POST['text'];

if (!(isset($from, $to, $message))) {
    echo 'A required field is missing';
    exit();
}

//$message = 'Hello, my name is Martin and Plankto is my friend. I am 15 years old, HonzaK is a very cool dude! I very much like PHP (the programming language).';
//$from = 'EN';
//$to = 'CS';
$languagesSupported = json_decode(file_get_contents('../languages.json'), 1);
if (!(in_array($from.'-'.$to, $languagesSupported))) {
    echo 'Language not supported!';
    exit();
}
$pronouns = json_decode(file_get_contents('../pronoun_dictionaries/'.$from.'-pronouns.json'), 1);
$languagePack = json_decode(file_get_contents('../language_packs/'.$from.'-'.$to.'.json'), 1);
$lp1 = [];
foreach ($languagePack as $f => $t) {
    if (!(isset($lp1[count(array_filter(explode(' ', $f)))]))) $lp1[count(array_filter(explode(' ', $f)))] = [];
    array_push($lp1[count(array_filter(explode(' ', $f)))], [$f, $t]);
}
krsort($lp1);
$punctuation = str_split(preg_replace('/[^.?!;()]/', '', $message));
$translatedText = '';
$correctTranslations = 0;
$incorrectTranslations = 0;
$queue = json_decode(file_get_contents('../translation_queues/'.$from.'-'.$to.'.json'), 1);
foreach (preg_split('/[.?!;()]/', $message) as $sKey => $sentence) {
    $translatedIndexes = [];
    $splitSentence = array_values(array_filter(explode(' ', $sentence)));
    $wordsInSentence = count($splitSentence);
    foreach ($lp1 as $phraseLen => $phraseSets) foreach ($phraseSets as $phraseSet) {
        $phrase = $phraseSet[0];
        $phraseTo = $phraseSet[1];
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
                        if ($index2 == count($splitPhrase) - 1) $splitSentence[$index1] = $phraseTo; else $splitSentence[$index1] = '';
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
    foreach ($queue as $qk => $q) if ($q[0] == $sentence) unset($queue[$qk]);
    $queue = array_values($queue);
    if (count($queueTMP)) array_push($queue, [$sentence, $queueTMP]);
    if (!(isset($punctuation[$sKey]))) $punctuation[$sKey] = '';
    if ($punctuation[$sKey]) { if ($punctuation[$sKey] == '(') $punctuation[$sKey] = ' '.$punctuation[$sKey]; else $punctuation[$sKey] .= ' '; }
    $translatedText .= trim(implode(' ', array_values(array_filter($splitSentence)))).$punctuation[$sKey];
    $correctTranslations += count($translatedIndexes);
    $incorrectTranslations += count($unchangedIndexes);
}
$translatedText1 = str_split($translatedText);
foreach ($translatedText1 as $lKey => $l) {
    if ((preg_match('/[.?!;()]/', $l))&&(isset($translatedText1[$lKey + 1], $translatedText1[$lKey + 2]))) {
        if (($translatedText1[$lKey + 1] == ' ')&&(preg_match('/[.?!;]/', $translatedText1[$lKey + 2]))) {
            unset($translatedText1[$lKey + 1]);
        }
    }
}
$translatedText = implode('', $translatedText1);
function e($correctTranslations, $incorrectTranslations) { return ($correctTranslations / ($incorrectTranslations + $correctTranslations)) * 100; }
$accuracy = @e($correctTranslations, $incorrectTranslations);
if (intval($accuracy) == 0) $accuracy = '0';
echo json_encode(['status' => 200, 'text' => $translatedText, 'accuracy' => strval(intval($accuracy)).'%']);
file_put_contents('../translation_queues/'.$from.'-'.$to.'.json', json_encode($queue, 128));
