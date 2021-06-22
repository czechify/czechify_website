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
//if (isset($_GET['guild'])) $guildID = $_GET['guild'];

if (isset($action)) {
    if ($action == "fetch") {
        $db = json_decode(file_get_contents("./db.json"), 1);

        if (isset($pp, $cleanup)) echo '<pre>'.cleanup(json_encode($db, 128)).'</pre>';
        elseif (isset($pp)) echo '<pre>'.json_encode($db, 128).'</pre>';
        elseif (isset($cleanup)) echo cleanup(json_encode($db), 128);
        else echo json_encode($db);
    }elseif ($action == "log") {
        if (isset($data)) {
            if ($data) {
                $db = json_decode(file_get_contents("./db.json"), 1);

                if (isset($data['thanker'], $data['thanking'], $data['message'])) {

                    if (!(isset($db[$data['thanker']]))) $db[$data['thanker']] = ['thanked' => [], 'was_thanked' => []];
                    array_push($db[$data['thanker']]['thanked'], ['time' => time(), 'message' => $data['message'], 'thanked' => $data['thanking']]);

                    if (!(isset($db[$data['thanking']]))) $db[$data['thanking']] = ['thanked' => [], 'was_thanked' => []];
                    array_push($db[$data['thanking']]['was_thanked'], ['time' => time(), 'message' => $data['message'], 'thanked_by' => $data['thanker']]);

                    $myfile = fopen("db.json", "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($db, 128));
                    fclose($myfile);

                    echo "Data uploaded and processed";
                }else {
                    echo "Sort your shit out";
                }
            }else{
                echo "Data is not valid";
            }
        }else{
            echo "Data has not been supplied";
        }
    }else{
        echo "Action is invalid";
    }
}else{
    echo "Action has not been supplied";
}


?>
