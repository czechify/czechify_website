<?php

session_write_close();

header("Content-Type: application/json");

function logError() {

}

function addToErrors($error) {
    $_SERVER['errors'][] = $error;
}

function addToOutput($error) {
    $_SERVER['output'][] = $error;
}

function returnErrors($code, $a = '', $b = '', $c = '') {
    die(json_encode(['error' => ['code' => [http_response_code($code), http_response_code()][1], 'message' => $_SERVER['errors'][count($_SERVER['errors']) - 1]['message'], 'errors' => $_SERVER['errors'], 'status' => strtoupper(str_replace(' ', '_', $_SERVER['codeToText']($code)))]], $_SERVER['flags']));
}

function returnSuccess($code, $a = '', $b = '', $c = '') {
    die(json_encode(['success' => ['code' => [http_response_code($code), http_response_code()][1], 'message' => $_SERVER['output'][count($_SERVER['output']) - 1]['message'], 'output' => $_SERVER['output'], 'status' => strtoupper(str_replace(' ', '_', $_SERVER['codeToText']($code)))]], $_SERVER['flags']));
}

file_put_contents("./log.json", json_encode(array_merge(json_decode(file_get_contents("./log.json"), 1), [$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']]), 128));

$config = ['time_between_words' => 300, 'basicWords' => [1, 2, 3], 'default_flags' => 64|128|256];

if (substr(explode('?', $_SERVER['REQUEST_URI'])[0], -1) == '/') $endpoint = array_slice(explode('/', substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])))), 1, (0 - (strlen(explode('?', $_SERVER['REQUEST_URI'])[0]) - strlen(rtrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/'))))); else $endpoint = array_slice(explode('/', substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])))), 1);

if (isset($prettyPrint)) $_SERVER['flags'] = 64|128|256; else $_SERVER['flags'] = $config['default_flags'];

if (isset($redirect)) {
    $logFile = '../../apiAccessFile.json';
    if (!(file_exists($logFile))) file_put_contents($logFile, '[]');
    $logData = json_decode(file_get_contents($logFile), 1);
    $logData[] = [dirname($_SERVER['PHP_SELF']), $redirect, $_GET, time()];
    file_put_contents($logFile, json_encode($logData, 64|128|256));
}

if (!(isset($_SERVER['HTTP_AUTHORIZATION']))) $_SERVER['HTTP_AUTHORIZATION'] = 'martin';

if (!(isset($_SERVER['HTTP_AUTHORIZATION']))) returnErrors(401, addToErrors(['message' => 'The request is missing a valid Authentication Token.', 'domain' => 'global', 'reason' => 'Forbidden']));

$query = $mysqli->query("SELECT `token`, `granted_to` FROM `api_authentication` WHERE `game_access` = 1 AND `token` = '".$mysqli->escape_string($_SERVER['HTTP_AUTHORIZATION'])."'");
if ($query) while ($row = mysqli_fetch_assoc($query)) { $authData = $row; $auth = $mysqli->escape_string($row['token']); }
if (!(isset($auth))) returnErrors(401, addToErrors(['message' => 'The request is missing a valid Authentication Token.', 'domain' => 'global', 'reason' => 'Forbidden']));

$varsToCollect = ['baseWord', 'targetWord', 'value', 'identifier', 'identifiedValue', 'minValue', 'maxValue', 'userID', 'wordID', 'prettyPrint', 'random', 'minmax', 'msgCountData', 'channel', 'redirect'];
foreach ($_GET as $a => $b) if (in_array($a, $varsToCollect)) eval("$$a = \$_GET['$a'];");

if ($endpoint[0] == 'accounts') {
    if ($endpoint[1] == 'retrieve') {
        if (!(isset($endpoint[2])&&($endpoint[2]))) returnErrors(400, addToErrors(['message' => 'The request is missing a valid user id.', 'domain' => 'global', 'reason' => 'Bad Request']));
        if ($authData['granted_to'] == 'discord_bot') {
            $userID = $mysqli->escape_string($endpoint[2]);
            $query = $mysqli->query("SELECT `id`, `deactivated` FROM `user_data` WHERE `discord_identifier` = '$userID'");
            if ($query) while ($row = $query->fetch_assoc()) if ($row['deactivated']) returnErrors(400, addToErrors(['message' => 'Error RUETLP53', 'domain' => 'global', 'reason' => 'Account deactivated'])); else returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $row['id']])); else returnErrors(500, addToErrors(['message' => 'Error 3NFX9EA8', 'domain' => 'global', 'reason' => 'Interal Server Error']));
            $query = $mysqli->query("SELECT `id`, `deactivated` FROM `tmp_user_data` WHERE `discord_identifier` = '$userID'");
            if ($query) while ($row = $query->fetch_assoc()) if ($row['deactivated']) returnErrors(400, addToErrors(['message' => 'Error V5A5N5WC', 'domain' => 'global', 'reason' => 'Account deactivated'])); else returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $row['id']])); else returnErrors(500, addToErrors(['message' => 'Error LYC6Q25L', 'domain' => 'global', 'reason' => 'Interal Server Error']));
            $id = $mysqli->escape_string(bin2hex(random_bytes(16)));
            $username = $mysqli->escape_string(bin2hex(random_bytes(6)));
            $time = $mysqli->escape_string(time());
            $query = $mysqli->query("INSERT INTO `tmp_user_data` (`id`, `username`, `discord_identifier`, `words_data`, `stats_data`, `thanks_data`, `time_created`, `deactivated`) VALUES ('$id', 'TMP_$username', '$userID', '{}', '{}', '{}', '$time', '0')");
            if ($query) returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $id])); else returnErrors(500, addToErrors(['message' => 'Error BKDA3232', 'domain' => 'global', 'reason' => 'Interal Server Error']));
        }elseif ($authData['granted_to'] == 'minecraft_server') {
            $userID = $mysqli->escape_string($endpoint[2]);
            $query = $mysqli->query("SELECT `id`, `deactivated` FROM `user_data` WHERE `minecraft_identifier` = '$userID'");
            if ($query) while ($row = $query->fetch_assoc()) if ($row['deactivated']) returnErrors(400, addToErrors(['message' => 'Error UYBHH3PC', 'domain' => 'global', 'reason' => 'Account deactivated'])); else returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $row['id']])); else returnErrors(500, addToErrors(['message' => 'Error BBK4UHE4', 'domain' => 'global', 'reason' => 'Interal Server Error']));
            returnErrors(400, addToErrors(['message' => 'Error 6UTDW3SD', 'domain' => 'global', 'reason' => 'Account not found']));
        }
        returnErrors(401, addToErrors(['message' => 'The request is missing an Authentication Token with permission to use this.', 'domain' => 'global', 'reason' => 'Forbidden']));
    }
}elseif ($endpoint[0] == 'user') {
    if (!((isset($endpoint[1]))&&($endpoint[1]))) returnErrors(400, addToErrors(['message' => 'The request is missing a valid user id.', 'domain' => 'global', 'reason' => 'Bad Request']));
    if ($endpoint[2] == 'words') {
        if ($endpoint[3] == 'allocate') {
            $userID = $mysqli->escape_string($endpoint[1]);
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `id` = '$userID' AND `deactivated` = 0");
            if (!($query)) returnErrors(500, addToErrors(['message' => 'Error HZK7SPJ8', 'domain' => 'global', 'reason' => 'Internal Server Error']));
            while ($row = $query->fetch_assoc()) {
                $userData = $row;
                $wordsData = json_decode($userData['words_data'], 1);

                if (!(($wordsData)&&(gettype($wordsData) == 'array')&&(isset($wordsData['words']))&&(isset($wordsData['last_given']))&&(gettype($wordsData['words']) == 'array')&&(gettype($wordsData['last_given']) == 'integer'))) { $wordsData = ['words' => [], 'last_given' => 0]; addToErrors(['message' => 'The user\'s words_data was invalid and has been emptied.', 'domain' => 'global', 'reason' => 'Internal Server Error']); }

                if ($wordsData['last_given'] + $config['time_between_words'] > time()) returnErrors(403, addToErrors(['flag' => 'TOO_FAST', 'message' => 'This user needs to wait '.strval($wordsData['last_given'] + $config['time_between_words'] - time()).' seconds until they can get another word.', 'domain' => 'global', 'reason' => 'Forbidden', 'time' => $wordsData['last_given'] + $config['time_between_words'] - time()]));

                $sql = $sql1 = '';
                foreach ($wordsData['words'] as $word) $sql .= "AND NOT `word_id` = '".$mysqli->escape_string($word)."' ";
                foreach ($config['basic_words'] as $k => $value) if ($k) $sql1 .= "OR `value` = '".$mysqli->escape_string($value)."' "; else $sql1 .= "`value` = '".$mysqli->escape_string($value)."' ";

                $query = $mysqli->query("SELECT `word_id` FROM `translate_data` WHERE `base_language` = 'EN_GB' AND `target_language` = 'CS_CZ' AND `game_approved` = 1 $sql AND ($sql1)");
                $words = mysqli_fetch_all($query);

                if (count($words)) {
                    $wordID = $words[array_rand($words)][0];
                    $wordsData['words'][] = $wordID;
                    $wordsData['last_given'] = time();
                    $jsonWordsData = $mysqli->escape_string(json_encode($wordsData, 64|256));
                    $mysqli->query("UPDATE `user_data` SET `words_data` = '$jsonWordsData' WHERE `id` = '$userID'");
                    if (($mysqli->query("SELECT `word_id`, `word_base`, `word_target` FROM `translate_data` WHERE `word_id` = '".$mysqli->escape_string($wordID)."'"))&&(@mysqli_fetch_assoc($mysqli->query("SELECT `word_id`, `word_base`, `word_target` FROM `translate_data` WHERE `word_id` = '".$mysqli->escape_string($wordID)."'")))) $data = mysqli_fetch_assoc($mysqli->query("SELECT `word_id`, `word_base`, `word_target` FROM `translate_data` WHERE `word_id` = '".$mysqli->escape_string($wordID)."'"));
                    if (isset($data)) returnSuccess(200, addToOutput(['message' => 'Word added.', 'domain' => 'global', 'word_data' => $data])); else returnErrors(500, addToErrors(['message' => 'Failed to fetch word data.', 'domain' => 'global', 'reason' => 'Internal Server Error']));
                }else returnErrors(204, addToErrors(['flag' => 'HAS_ALL', 'message' => 'This user already has all awardable words.', 'domain' => 'global', 'reason' => 'No Content']));
            }
            if (isset($userData)) exit();
            $query = $mysqli->query("SELECT * FROM `tmp_user_data` WHERE `id` = '$userID' AND `deactivated` = 0");
            if (!($query)) returnErrors(500, addToErrors(['message' => 'Error HZK7SPJ8', 'domain' => 'global', 'reason' => 'Internal Server Error']));
            while ($row = $query->fetch_assoc()) {
                $userData = $row;
                addToErrors(['message' => 'Temp account ID parsed (Request most likely from discord).', 'domain' => 'global', 'reason' => 'Bad Request']);
                $wordsData = json_decode($userData['words_data'], 1);
                if (!(($wordsData)&&(gettype($wordsData) == 'array')&&(isset($wordsData['words']))&&(isset($wordsData['last_given']))&&(gettype($wordsData['words']) == 'array')&&(gettype($wordsData['last_given']) == 'integer'))) { $wordsData = ['words' => [], 'last_given' => 0]; addToErrors(['message' => 'The user\'s words_data was invalid and has been emptied.', 'domain' => 'global', 'reason' => 'Internal Server Error']); }
                if ($wordsData['last_given'] + $config['time_between_words'] > time()) returnErrors(403, addToErrors(['flag' => 'TOO_FAST', 'message' => 'This user needs to wait '.strval($wordsData['last_given'] + $config['time_between_words'] - time()).' seconds until they can get another word.', 'domain' => 'global', 'reason' => 'Forbidden', 'time' => $wordsData['last_given'] + $config['time_between_words'] - time()]));
                $sql = $sql1 = '';
                foreach ($wordsData['words'] as $word) $sql .= "AND NOT `word_id` = '".$mysqli->escape_string($word)."' ";
                foreach ($config['basic_words'] as $k => $value) if ($k) $sql1 .= "OR `value` = '".$mysqli->escape_string($value)."' "; else $sql1 .= "`value` = '".$mysqli->escape_string($value)."' ";
                $query = $mysqli->query("SELECT `word_id` FROM `translate_data` WHERE `base_language` = 'EN_GB' AND `target_language` = 'CS_CZ' AND `game_approved` = 1 $sql AND ($sql1)");
                $words = mysqli_fetch_all($query);
                if (count($words)) {
                    $wordID = $mysqli->escape_string($words[array_rand($words)][0]);
                    $wordsData['words'][] = $wordID;
                    $wordsData['last_given'] = time();
                    $jsonWordsData = $mysqli->escape_string(json_encode($wordsData, 64|256));
                    $mysqli->query("UPDATE `tmp_user_data` SET `words_data` = '$jsonWordsData' WHERE `id` = '$userID'");
                    $query = $mysqli->query("SELECT `word_id`, `word_base`, `word_target` FROM `translate_data` WHERE `word_id` = '$wordID'");
                    if ($query) while ($row = $query->fetch_assoc()) $data = $row; else returnErrors(500, addToErrors(['message' => 'Failed to fetch word data. Query invalid', 'domain' => 'global', 'reason' => 'Internal Server Error']));
                    if (isset($data)) returnSuccess(200, addToOutput(['message' => 'Word added.', 'domain' => 'global', 'word_data' => $data])); else returnErrors(500, addToErrors(['message' => 'Failed to fetch word data.', 'domain' => 'global', 'reason' => 'Internal Server Error']));
                }else returnErrors(204, addToErrors(['flag' => 'HAS_ALL', 'message' => 'This user already has all awardable words.', 'domain' => 'global', 'reason' => 'No Content']));
            }
            exit();
        }
    }
}

returnErrors(400, addToErrors(['message' => 'The request is missing a valid action.', 'domain' => 'global', 'reason' => 'Bad Request']));
if (isset($action)) {
    if ($action == "getUserData") {
        if (isset($userID)) {
            if (isset($userData[$userID])) {
                $wordData = array();
                foreach ($userData[$userID]['words'] as $word) {
                    if (isset($allWords[$word])) {
                        array_push($wordData, $allWords[$word]);
                    }else{
                        if (array_search($word, $userData[$userID]['words']) !== false) {
                            unset($userData[$userID]['words'][array_search($word, $userData[$userID]['words'])]);
                            $userData[$userID]['words'] = array_values($userData[$userID]['words']);
                        }
                    }
                }
                $score = 0;
                foreach ($wordData as $word) $score = $score + $word['value'];

                if (isset($prettyPrint)) {
                    echo json_encode(['words' => $wordsData, 'score' => $score], $flags);
                }
                file_put_contents("userData.json", json_encode($userData));
            }else{
                echo "User not found";
            }
        }else{
            echo "You missed something";
        }
    }elseif ($action == "addToUser") {
        $userData = json_decode(file_get_contents("./userData.json"), 1);
        $allWords = json_decode(file_get_contents("./allWords.json"), 1);
        if (isset($random, $userID)) {
            if (isset($minValue, $maxValue, $minmax)) {
                //If user not in DB add them
                if (!(isset($userData[$userID]))) {
                    $userData[$userID] = ["words" => []];
                }
                //find a random word that isnt taken and satisfies the $minValue and $maxValue
                //get all the words that fit the requirements
                $wordsThatFit = [];
                foreach ($allWords as $word) {
                    if (($word['value'] >= intval($minValue))&&($word['value'] <= intval($maxValue))&&(!(in_array(strval(array_search($word, $allWords)), $userData[$userID]['words'])))) array_push($wordsThatFit, $word);
                }
                //choose a word at random if there are any
                if (count($wordsThatFit)) {
                    $randomWordID = strval(array_search($wordsThatFit[array_rand($wordsThatFit)], $allWords));
                    array_push($userData[$userID]['words'], $randomWordID);
                    file_put_contents("./userData.json", json_encode($userData, JSON_PRETTY_PRINT));
                    echo "Added word: ".$randomWordID;
                }else{
                    echo "User has all applicable words";
                }
            }else{
                //If user not in DB add them
                if (!(isset($userData[$userID]))) {
                    $userData[$userID] = ['words' => []];
                }
                //find a random word that isnt taken
                //get all the words that fit the requirements
                $wordsThatFit = [];
                foreach ($allWords as $word) {
                    if (!(in_array(strval(array_search($word, $allWords)), $userData[$userID]['words']))) array_push($wordsThatFit, $word);
                }
                //choose a word at random if there are any
                if (!(count($wordsThatFit) == 0)) {
                    $randomWordID = array_search($wordsThatFit[array_rand($wordsThatFit)], $allWords);
                    array_push($userData[$userID]['words'], strval($randomWordID));
                    file_put_contents("./userData.json", json_encode($userData, JSON_PRETTY_PRINT));
                    echo "Added word: ".$randomWordID;
                }else{
                    echo "User has all applicable words";
                }
            }
        }elseif (isset($wordID, $userID)){
            if (isset($allWords[$wordID])) {
                if (!(in_array($wordID, $userData[$userID]['words']))) {
                    if (!(isset($userData[$userID]))) {
                        $userData[$userID] = ["words" => []];
                    }
                    array_push($userData[$userID]['words'], $wordID);

                    file_put_contents("./userData.json", json_encode($userData, JSON_PRETTY_PRINT));
                    echo "Added word: ".$wordID;
                }else{
                    echo "User already has word";
                }
            }else{
                echo "WordID does not exist";
            }
        }else{
            echo "You missed something";
        }
    }elseif ($action == "removeFromUser") {
        $userData = json_decode(file_get_contents("./userData.json"), 1);
        $allWords = json_decode(file_get_contents("./allWords.json"), 1);
        if (isset($random, $userID)) {
            if (isset($minValue, $maxValue, $minmax)) {
                //If user in DB
                if (isset($userData[$userID])) {
                    //check the user has the word
                    //find a random word that is taken and satisfies the $minValue and $maxValue
                    //get all the words that fit the requirements
                    $wordsThatFit = [];
                    foreach ($allWords as $word) {
                        if (($word['value'] >= intval($minValue))&&($word['value'] <= intval($maxValue))&&(in_array(strval(array_search($word, $allWords)), $userData[$userID]['words']))) array_push($wordsThatFit, $word);
                    }
                    //choose a word at random if there are any
                    if (!(count($wordsThatFit) == 0)) {
                        $randomWordID = array_search($wordsThatFit[array_rand($wordsThatFit)], $allWords);
                        unset($userData[$userID]['words'][array_search(strval($randomWordID), $userData[$userID]['words'])]);
                        $userData[$userID]['words'] = array_values($userData[$userID]['words']);
                        file_put_contents("./userData.json", json_encode($userData, JSON_PRETTY_PRINT));
                        echo "Removed word: ".$randomWordID;
                    }else{
                        echo "User doesn't have any applicable words";
                    }
                }else{
                    echo "User doesn't exist";
                }
            }else{
                //If user in DB
                if (isset($userData[$userID])) {
                    //find a random word that isnt taken
                    //get all the words that fit the requirements
                    $wordsThatFit = [];
                    foreach ($allWords as $word) {
                        if (in_array(strval(array_search($word, $allWords)), $userData[$userID]['words'])) array_push($wordsThatFit, $word);
                    }
                    //choose a word at random if there are any
                    if (!(count($wordsThatFit) == 0)) {
                        $randomWordID = array_rand($allWords);
                        unset($userData[$userID]['words'][array_search(strval($randomWordID), $userData[$userID]['words'])]);
                        $userData[$userID]['words'] = array_values($userData[$userID]['words']);
                        file_put_contents("./userData.json", json_encode($userData, JSON_PRETTY_PRINT));
                        echo "Removed word: ".$randomWordID;
                    }else{
                        echo "User doesn't have any applicable words";
                    }
                }else{
                    echo "User doesn't exist";
                }
            }
        }elseif (isset($wordID, $userID)){
            if (isset($userData[$userID])) {
                if (in_array(strval($wordID), $userData[$userID]['words'])) {
                    unset($userData[$userID]['words'][array_search(strval($wordID), $userData[$userID]['words'])]);
                    $userData[$userID]['words'] = array_values($userData[$userID]['words']);
                    file_put_contents("./userData.json", json_encode($userData, JSON_PRETTY_PRINT));
                    echo "Removed word: ".$wordID;
                }else{
                    echo "User doesn't have any applicable words";
                }
            }else{
                echo "User doesn't exist";
            }
        }else{
            echo "You missed something";
        }
    }elseif ($action == "newWord") {
        if (isset($wordCzech, $wordEnglish, $value)) {
            $allWords = json_decode(file_get_contents("./allWords.json"), 1);
            if (isset($allWords)) {
                $newWordID = rand(10000, 99999);
                while (isset($wllWords[strval($newWordID)])) {
                    $newWordID = rand(10000, 99999);
                }
                $allWords[strval($newWordID)] = ["wordCzech" => $wordCzech, "wordEnglish" => $wordEnglish, "value" => json_decode($value, 1)];

                file_put_contents("./allWords.json", json_encode($allWords, JSON_PRETTY_PRINT));
                echo "Complete";
            }else{
                echo "DB is corrupted, oops.";
            }
        }else{
            echo "You missed something";
        }
    }elseif ($action == "editWord") {
        if (isset($identifier, $identifiedValue, $wordCzech, $wordEnglish, $value)) {
            if ($identifier == "value") {
                echo "This is not allowed, please choose another identifier";
            }else {
                $allWords = json_decode(file_get_contents("./allWords.json"), 1);
                if (isset($allWords)) {
                    if ($identifier == "index") {
                        if (isset($allWords[$identifiedValue])) {
                            $allWords[$identifiedValue]['wordCzech'] = $wordCzech;
                            $allWords[$identifiedValue]['wordEnglish'] = $wordEnglish;
                            $allWords[$identifiedValue]['value'] = intval($value);
                            echo "Complete";
                        }else{
                            echo "This index does not exist";
                        }
                    }else{
                        foreach ($allWords as $allWord) {
                            if ($allWord[$identifier] == $identifiedValue) {
                                if (array_search($allWord, $allWords) !== false) {
                                    $index = array_search($allWord, $allWords);
                                }
                            }
                        }
                    }

                    if (isset($index)) {
                        if (isset($allWords[$index])) {
                            $allWords[$index]['wordCzech'] = $wordCzech;
                            $allWords[$index]['wordEnglish'] = $wordEnglish;
                            $allWords[$index]['value'] = intval($value);
                            echo "Complete";
                        }
                    }else{
                        echo "We cannot find a wordset with that identifier, try something else";
                    }

                    file_put_contents("./allWords.json", json_encode($allWords, JSON_PRETTY_PRINT));
                }else{
                    echo "DB is corrupted, oops.";
                }
            }
        }else{
            echo "You missed something";
        }
    }elseif ($action == "deleteWord") {
        if (isset($identifier, $identifiedValue)) {
            if ($identifier == "value") {
                echo "This is not allowed, please choose another identifier";
            }else {
                $allWords = json_decode(file_get_contents("./allWords.json"), 1);
                if (isset($allWords)) {
                    if ($identifier == "index") {
                        if (isset($allWords[$identifiedValue])) {
                            unset($allWords[$identifiedValue]);
                            echo "Complete";
                        }else{
                            echo "This index does not exist";
                        }
                    }else{
                        foreach ($allWords as $allWord) {
                            if ($allWord[$identifier] == $identifiedValue) {
                                if (array_search($allWord, $allWords) !== false) {
                                    $index = array_search($allWord, $allWords);
                                }
                            }
                        }
                    }

                    if (isset($index)) {
                        if (isset($allWords[$index])) {
                            unset ($allWords[$index]);
                            echo "Complete";
                        }
                    }else{
                        echo "We cannot find a wordset with that identifier, try something else";
                    }

                    file_put_contents("./allWords.json", json_encode($allWords, JSON_PRETTY_PRINT));
                }else{
                    echo "DB is corrupted, oops.";
                }
            }
        }else{
            echo "You missed something";
        }
    }elseif ($action == "viewAllWords") {
        $allWords = json_decode(file_get_contents("./allWords.json"), 1);
        if (isset($allWords)) {
            if (isset($cleanUp)) {
                if (isset($prettyPrint)) {
                    echo "<pre>".cleanUp(json_encode(json_decode(file_get_contents("./allWords.json"), 1), JSON_PRETTY_PRINT))."</pre>";
                }else{
                    echo cleanUp(json_encode(json_decode(file_get_contents("./allWords.json"), 1)));
                }
            }else{
                if (isset($prettyPrint)) {
                    echo "<pre>".json_encode(json_decode(file_get_contents("./allWords.json"), 1), JSON_PRETTY_PRINT)."</pre>";
                }else{
                    echo json_encode(json_decode(file_get_contents("./allWords.json"), 1));
                }
            }
        }else{
            echo "DB is corrupted, oops.";
        }
    }elseif ($action == "viewUserData") {
        $allWords = json_decode(file_get_contents("./userData.json"), 1);
        if (isset($allWords)) {
            if (isset($cleanUp)) {
                if (isset($prettyPrint)) {
                    echo "<pre>".cleanUp(json_encode(json_decode(file_get_contents("./userData.json"), 1), JSON_PRETTY_PRINT))."</pre>";
                }else{
                    echo cleanUp(json_encode(json_decode(file_get_contents("./userData.json"), 1)));
                }
            }else{
                if (isset($prettyPrint)) {
                    echo "<pre>".json_encode(json_decode(file_get_contents("./userData.json"), 1), JSON_PRETTY_PRINT)."</pre>";
                }else{
                    echo json_encode(json_decode(file_get_contents("./userData.json"), 1));
                }
            }
        }else{
            echo "DB is corrupted, oops.";
        }
    }/*elseif ($action == "addToMessageCount") {
        if (isset($msgCountData)) {
            if (isset($channel)) {
                if ($channel == "yourChannelID") {
                    echo "fuck off2";
                }else {
                    echo "fuck off";
                }
            }else{
                $msgData = json_decode(file_get_contents("./msgData.json"), 1);
                $date = date("d-m-Y");
                $newData = $msgCountData;

                foreach ($newData as $newUser) {
                    if (isset($msgData[$newUser]))
                }











                if (isset($newData))


                $msgData = ["270973904359653387" => ["count" => 5, "last_message" => "example"]];
            }
        }else{
            echo "You missed something";
        }
    }*/else {
        echo "That's not a valid action";
    }
}else{
    echo "You did not supply an action";
}

if (isset($redirect)) {
    echo '
    <script>
    setTimeout(function() {
        document.location.href = "'.$redirect.'";
    }, 1500)
    </script>
    ';
    header("Location: $redirect");
}

?>
