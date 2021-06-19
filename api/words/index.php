<?php

if (file_exists("./log.json")) $x = json_decode(file_get_contents("./log.json"), 1); else $x = [];
array_push($x, $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$myfile = fopen("./log.json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($x, 128));
fclose($myfile);

$userData = json_decode(file_get_contents("./userData.json"), 1);
$allWords = json_decode(file_get_contents("./allWords.json"), 1);
foreach ($userData as $user) {
    $userID = array_search($user, $userData);
    foreach ($userData[$userID]['words'] as $word) {
        if (!(isset($allWords[$word]))) {
            unset($userData[$userID]['words'][array_search($word, $userData[$userID]['words'])]);
            $userData[$userID]['words'] = array_unique(array_values($userData[$userID]['words']));
        }
    }
    $myfile = fopen("userData.json", "w") or die("Unable to open file!");
    fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
    fclose($myfile);
}

unset($userID, $word, $myfile, $user);

function cleanUp($text) {
    $str     = str_replace('\u','u',$text);
    $str_replaced = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $str);

    return $str_replaced;
}
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_GET['wordCzech'])) $wordCzech=$_GET['wordCzech'];
if (isset($_GET['wordEnglish'])) $wordEnglish=$_GET['wordEnglish'];
if (isset($_GET['value'])) $value=$_GET['value'];
if (isset($_GET['identifier'])) $identifier=$_GET['identifier'];
if (isset($_GET['identifiedValue'])) $identifiedValue=$_GET['identifiedValue'];
if (isset($_GET['minValue'])) $minValue=$_GET['minValue'];
if (isset($_GET['maxValue'])) $maxValue=$_GET['maxValue'];
if (isset($_GET['userID'])) $userID=$_GET['userID'];
if (isset($_GET['wordID'])) $wordID=$_GET['wordID'];
if (isset($_GET['cleanUp'])) $cleanUp=$_GET['cleanUp'];
if (isset($_GET['prettyPrint'])) $prettyPrint=$_GET['prettyPrint'];
if (isset($_GET['random'])) $random=$_GET['random'];
if (isset($_GET['minmax'])) $minmax=$_GET['minmax'];

if (isset($_GET['msgCountData'])) $msgCountData=$_GET['msgCountData'];
if (isset($_GET['channel'])) $channel=$_GET['channel'];


if (isset($_GET['redirect'])) $redirect=$_GET['redirect'];


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
                foreach ($wordData as $word) {
                    $score = $score + $word['value'];
                }
                if (isset($cleanUp)) {
                    if (isset($prettyPrint)) {
                        echo "<pre>".cleanUp(json_encode(json_decode(' { "words": '.json_encode($wordData).', "score": '.$score.' } ', 1), JSON_PRETTY_PRINT))."</pre>";
                    }else{
                        echo cleanUp(json_encode(json_decode(' { "words": '.json_encode($wordData).', "score": '.$score.' } ', 1)));
                    }
                }else{
                    if (isset($prettyPrint)) {
                        echo "<pre>".json_encode(json_decode(' { "words": '.json_encode($wordData).', "score": '.$score.' } ', 1), JSON_PRETTY_PRINT)."</pre>";
                    }else{
                        echo json_encode(json_decode(' { "words": '.json_encode($wordData).', "score": '.$score.' } ', 1));
                    }
                }
                $myfile = fopen("userData.json", "w") or die("Unable to open file!");
                fwrite($myfile, json_encode($userData));
                fclose($myfile);
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
                    $myfile = fopen("./userData.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
                    fclose($myfile);
                    echo "Added word: ".$randomWordID;
                }else{
                    echo "User has all applicable words";
                }
            }else{
                //If user not in DB add them
                if (!(isset($userData[$userID]))) {
                    $userData[$userID] = json_decode(' { "words": [] } ', 1);
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
                    $myfile = fopen("./userData.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
                    fclose($myfile);
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

                    $myfile = fopen("./userData.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
                    fclose($myfile);
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
                        $myfile = fopen("./userData.json", "w") or die("Unable to open file!");
                        fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
                        fclose($myfile);
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
                        $myfile = fopen("./userData.json", "w") or die("Unable to open file!");
                        fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
                        fclose($myfile);
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
                    $myfile = fopen("./userData.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($userData, JSON_PRETTY_PRINT));
                    fclose($myfile);
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
                
                $myfile = fopen("./allWords.json", "w") or die("Unable to open file!");
                fwrite($myfile, json_encode($allWords, JSON_PRETTY_PRINT));
                fclose($myfile);
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

                    $myfile = fopen("./allWords.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($allWords, JSON_PRETTY_PRINT));
                    fclose($myfile);
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

                    $myfile = fopen("./allWords.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($allWords, JSON_PRETTY_PRINT));
                    fclose($myfile);
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