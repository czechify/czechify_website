<?php

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

function cleanUp($text) {
    return preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', str_replace('\u','u', $text));
}

if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_GET['pretty_print'])) $pp = $_GET['pretty_print'];
if (isset($_GET['cleanup'])) $cleanup = $_GET['cleanup'];
if (isset($_GET['data'])) $data = json_decode($_GET['data'], 1);
if (isset($_GET['guild'])) $guildID = $_GET['guild'];

if (isset($action)) {
    if ($action == "fetch") {
        $db = json_decode(file_get_contents("./db.json"), 1);
        foreach ($db['users'] as $user) {
            $userID = strval(array_search($user, $db['users']));
            $tt = 0;
            foreach ($user['withOthers'] as $user1) $tt += $user1;
            $db['users'][$userID]['total'] = $tt + $user['alone'];
        }
        if (isset($pp, $cleanup)) echo '<pre>'.cleanup(json_encode($db, 128)).'</pre>';
        elseif (isset($pp)) echo '<pre>'.json_encode($db, 128).'</pre>';
        elseif (isset($cleanup)) echo cleanup(json_encode($db), 128);
        else echo json_encode($db);
    }elseif ($action == "log") {
        if (isset($data, $guildID)) {
            if (($data)&&($guildID)) {
                $db = json_decode(file_get_contents("./db.json"), 1);
                if (isset($db[$guildID]['previousLog']['data'])) {
                    $timeDiff = time() - $db[$guildID]['previousLog']['time'];
                    if (!($timeDiff)) sleep(1);
                    $timeDiff = time() - $db[$guildID]['previousLog']['time'];
                    foreach ($db[$guildID]['previousLog']['data'] as $channel) {
                        foreach ($channel['members'] as $member) {
                            if (!(isset($db['users'][$member]))) {
                                $db['users'][$member] = ['withOthers' => [], 'alone' => 0];
                                if (count($channel['members']) == 1) $db['users'][$member]['alone'] = $timeDiff;
                                foreach ($channel['members'] as $member1) if (!($member1 == $member)) $db['users'][$member]['withOthers'][$member1] = $timeDiff;
                            }else{
                                if (count($channel['members']) == 1) $db['users'][$member]['alone'] += $timeDiff;
                                foreach ($channel['members'] as $member1) if (!($member1 == $member)) $db['users'][$member]['withOthers'][$member1] += $timeDiff;
                            }
                        }
                    }
                }

                $db[$guildID]['previousLog']['data'] = $data;
                $db[$guildID]['previousLog']['time'] = time();

                $myfile = fopen("db.json", "w") or die("Unable to open file!");
                fwrite($myfile, json_encode($db, 128));
                fclose($myfile);
                
                echo "Data uploaded and processed";
            }else{
                echo "Data or GuildID are not valid";
            }
        }else{
            echo "Data or GuildID have been supplied";
        }
    }else{
        echo "Action is invalid";
    }
}else{
    echo "Action has not been supplied";
}


?>