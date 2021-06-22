<?php

session_start();

if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP']; elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; else $ip = $_SERVER['REMOTE_ADDR'];

if (!(isset($_SESSION['id']))) $_SESSION['id'] = 'SID'.bin2hex(random_bytes(32));

function mb_ucfirst($string, $encoding = 'UTF-8') {
    return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding).mb_substr($string, 1, null, $encoding);
}

function checkLogin($data) {
    if (isset($data['ip'], $data['db'], $data['fName'])) {
        if (isset($data['db']['sessions'][$_SESSION['id']]['ip'])) {
            if ($data['db']['sessions'][$_SESSION['id']]['ip'] == $data['ip']) {
                if ((intval(time()) - intval($data['db']['sessions'][$_SESSION['id']]['time'])) < 1800) {
                    if (isset($data['db']['data'][$data['db']['sessions'][$_SESSION['id']]['email']])) return ["status" => 200, "data" => $data['db']['sessions'][$_SESSION['id']], 'furtherData' => $data['db']['data'][$data['db']['sessions'][$_SESSION['id']]['email']]]; else return ["status" => 403, "error" => "Access Denied", "reason" => "Účet již neexistuje"];
                }else{
                    unset($data['db']['sessions'][$_SESSION['id']]);
                    $myfile = fopen($data['fName'], "w") or die("Unable to open file!");
                    fwrite($myfile, json_encode($data['db'], 128));
                    fclose($myfile);
                    return ["status" => 403, "error" => "Access Denied", "reason" => "Čas vypršel"];
                }
            }
        }
    }
    return ["status" => 403];
}

function register($ip, $email, $db, $dbFileName, $username, $password, $google, $facebook, $vk, $github, $discord, $twitter) {
    if ((isset($ip, $email, $db, $dbFileName, $username))&&(($password)||($google)||($facebook)||($vk)||($github)||($discord)||($twitter))) {
        if (!(filter_var($data['email'], FILTER_VALIDATE_EMAIL))) return ["status" => 403, "error" => "Access Denied", "reason" => "Invalid Email"];
        if (isset($data['password'])) {
            if (strlen($data['password']) < 8) return ["status" => 403, "error" => "Access Denied", "reason" => "Password is less than 8 characters long"];
            if (!(preg_match('/[A-Z]/', $data['password']))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain an uppercase character"];
            if (!(preg_match('/[a-z]/', $data['password']))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain a lowercase character"];
            if (!(preg_match('/\d/', $data['password']))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain a number"];
            if (!(preg_match('/[^a-zA-Z\d]/', $data['password']))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain a special character"];
        }
        if (isset($data['db']['data'][$data['email']])) return ["status" => 403, "error" => "Access Denied", "reason" => "Email is taken"];
        if (isset($data['googleID'])) foreach ($data['db']['data'] as $u) if ($data['googleID'] == $u['googleID']) return ["status" => 403, "error" => "Access Denied", "reason" => "Google account is already associated with an account"];
        if (isset($data['facebookID'])) foreach ($data['db']['data'] as $u) if ($data['facebookID'] == $u['facebookID']) return ["status" => 403, "error" => "Access Denied", "reason" => "Facebook account is already associated with an account"];

        $id = rand(11111111, 99999999);
        while (isset($data['db']['idR'][strval($id)])) $id = rand(11111111, 99999999);
        $data['db']['idR'][strval($id)] = $data['email'];
        $data['db']['data'][$data['email']] = ['id' => strval($id), 'name' => $data['name'], 'time' => time(), 'icon' => '', 'products' => [], 'reviews' => [], 'ip' => [], 'mailingList_novinky' => true, 'mailingList_nejprodavanejsi-produkty' => true, 'mailingList_doporucene-produkty' => true, 'emailVerified' => ((isset($data['googleID']))||(isset($data['facebookID'])))];
        if (isset($data['password'])) $data['db']['data'][$data['email']]['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if (isset($data['googleID'])) { $data['db']['data'][$data['email']]['googleID'] = $data['googleID']; $data['db']['data'][$data['email']]['googleEmail'] = $data['email']; }
        if (isset($data['facebookID'])) { $data['db']['data'][$data['email']]['facebookID'] = $data['facebookID']; $data['db']['data'][$data['email']]['facebookEmail']; }

        return login($data);
    }else{
        return ["status" => 403, "error" => "Access Denied", "reason" => "Chybné informace"];
    }
}

function login($data) {
    if ((isset($data['ip'], $data['db'], $data['fName']))&&((isset($data['password']))||(isset($data['googleID']))||(isset($data['facebookID'])))&&((isset($data['email']))||(isset($data['googleID']))||(isset($data['facebookID'])))) {
        if (isset($data['googleID'])) {
            $data['email'] = '';
            foreach ($data['db']['data'] as $email => $user) if ((isset($user['googleID']))&&($user['googleID'] == $data['googleID'])) {
                $data['email'] = $email;
                break;
            }
            if (!($data['email'])) return ["status" => 403, "error" => "Access Denied", "reason" => "Chybné informace"];
        }elseif (isset($data['facebookID'])) {
            $data['email'] = '';
            foreach ($data['db']['data'] as $email => $user) if ((isset($user['facebookID']))&&($user['facebookID'] == $data['facebookID'])) {
                $data['email'] = $email;
                break;
            }
            if (!($data['email'])) return ["status" => 403, "error" => "Access Denied", "reason" => "Chybné informace"];
        }elseif (!(isset($data['db']['data'][$data['email']]))) return ["status" => 403, "error" => "Access Denied", "reason" => "Chybné informace"];

        //check if the password is incorrect
        if ((!((isset($data['facebookID']))||(isset($data['googleID']))))&&(!(password_verify($data['password'], $data['db']['data'][$data['email']]['password'])))) return ["status" => 403, "error" => "Access Denied", "reason" => "Chybné informace"];

        //double check if sessions(s) is/are present for this account, if there is/are, destroy it
        $m = [];
        foreach ($data['db']['sessions'] as $s) if ($s['email'] == $data['email']) {
            array_push($m, $s);
            $currentTokenIndex = array_search($s, $data['db']['sessions']);
            unset($data['db']['sessions'][$currentTokenIndex]);
        }
        //if the session doesnt have an id, make one
        if (!(isset($_SESSION['id']))) $_SESSION['id'] = session_create_id("SID");
        //create a new session object
        $newSessionObject = ["ip" => $data['ip'], "email" => $data['email'], "time" => time()];
        //add the new session to the 'type' object
        $data['db']['sessions'][$_SESSION['id']] = $newSessionObject;
        //add the ip address to the ip object with the timestamp
        while (isset($data['db']['data'][$data['email']]['ip'][time()])) { sleep(0.5); }
        $data['db']['data'][$data['email']]['ip'][time()] = $data['ip'];
        //ensure the ip array has no more than 5 entries per account, removing the oldest
        while (count($data['db']['data'][$data['email']]['ip']) > 5) unset($data['db']['data'][$data['email']]['ip'][array_keys($data['db']['data'][$data['email']]['ip'])[0]]);
        $data['db']['data'][$data['email']]['ip'] = array_values($data['db']['data'][$data['email']]['ip']);
        //save the shit

        $myfile = fopen($data['fName'], "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($data['db'], 128));
        fclose($myfile);
        return ["status" => 200, "data" => $newSessionObject];
    }else{
        return ["status" => 403, "error" => "Access Denied", "reason" => "Chybné informace"];
    }
}

function logout($data) {
    if (isset($data['db'], $data['fName'])) {
        if (isset($data['db']['sessions'][$_SESSION['id']])) {
            unset($data['db']['sessions'][$_SESSION['id']]);
            $myfile = fopen($data['fName'], "w") or die("Unable to open file!");
            fwrite($myfile, json_encode($data['db'], 128));
            fclose($myfile);
        }
    }
    return ["status" => 200];
}

$x = checkLogin(["ip" => $ip, "db" => $db, 'fName' => $ref.$config['dbPath']]);

$loggedIn = $x['status'] == 200;

?>
