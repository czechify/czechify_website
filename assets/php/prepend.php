<?php

if (isset($_GET['b'])) {
    ini_set('xdebug.var_display_max_depth', -1);
    ini_set('xdebug.var_display_max_children', -1);
    ini_set('xdebug.var_display_max_data', -1);
}

$mysqli = new mysqli("localhost", "czechify", 'ZnXwO4kfhitpVDxw', "czechify");

$query = $mysqli->query("SELECT `session_id` FROM `sessions_data` WHERE `time_expire` < ".time());
if ($query) while ($row = mysqli_fetch_assoc($query)) $mysqli->query("DELETE FROM `sessions_data` WHERE `session_id` = '".mysqli_real_escape_string($mysqli, $row['session_id'])."'");

$query = $mysqli->query("SELECT `token` FROM `api_authentication` WHERE `expires` < ".time());
if ($query) while ($row = mysqli_fetch_assoc($query)) $mysqli->query("DELETE FROM `api_authentication` WHERE `token` = '".mysqli_real_escape_string($mysqli, $row['token'])."'");

if (isset($_GET['b'])) {
    $x = checkLogin($ip, $mysqli);

    $x = login($ip, $mysqli, 'martin', '@123Martin');

    $x = checkLogin($ip, $mysqli);

    $x = logout($mysqli);

    $x = checkLogin($ip, $mysqli);

    $x = register($ip, 'martin@najemi.cz', $mysqli, 'martin', '@123Martin');
    var_dump($x);

    exit();
}

function mb_ucfirst($string, $encoding = 'UTF-8') {
    return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding).mb_substr($string, 1, null, $encoding);
}

function checkLogin($ip, $mysqli) {
    $ip = mysqli_real_escape_string($mysqli, $ip);
    $query = $mysqli->query("SELECT `account_id`, `ip` FROM `sessions_data` WHERE `session_id` = '".mysqli_real_escape_string($mysqli, $_SESSION['id'])."'");
    while ($row = mysqli_fetch_assoc($query)) {
        $query1 = $mysqli->query("SELECT * FROM `user_data` WHERE `id` = '".mysqli_real_escape_string($mysqli, $row['account_id'])."'");
        if ($query1) while ($row1 = mysqli_fetch_assoc($query1)) if ((isset($row1['ip']))&&($row1['ip'] == $ip)&&($ip == $row['ip'])) return ['status' => 200, 'data' => $row1]; else $mysqli->query("DELETE FROM `sessions_data` WHERE `session_id` = '".mysqli_real_escape_string($mysqli, $_SESSION['id'])."'"); else $mysqli->query("DELETE FROM `sessions_data` WHERE `session_id` = '".mysqli_real_escape_string($mysqli, $_SESSION['id'])."'");
    }
    return ["status" => 403];
}

function register($ip, $email, $mysqli, $username, $password = '', $google = '', $facebook = '', $vk = '', $github = '', $discord = '', $twitter = '') {
    $ip = mysqli_real_escape_string($mysqli, $ip);
    if (($password)||($google)||($facebook)||($vk)||($github)||($discord)||($twitter)) {
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) return ["status" => 403, "error" => "Access Denied", "reason" => "Invalid Email"];
        if ($password) {
            if (strlen($password) < 8) return ["status" => 403, "error" => "Access Denied", "reason" => "Password is less than 8 characters long"];
            if (!(preg_match('/[A-Z]/', $password))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain an uppercase character"];
            if (!(preg_match('/[a-z]/', $password))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain a lowercase character"];
            if (!(preg_match('/\d/', $password))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain a number"];
            if (!(preg_match('/[^a-zA-Z\d]/', $password))) return ["status" => 403, "error" => "Access Denied", "reason" => "Password doesn't contain a special character"];
        }
        $username_safe = mysqli_real_escape_string($mysqli, $username);
        $email_safe = mysqli_real_escape_string($mysqli, $email);
        $query = $mysqli->query("SELECT * FROM `user_data` WHERE `email` = '$email_safe'");
        if (($query)&&(mysqli_fetch_assoc($query))) return ["status" => 403, "error" => "Access Denied", "reason" => "Email is taken"];
        if (isset($db[''])) return ["status" => 403, "error" => "Access Denied", "reason" => "Username is taken"];
        if ($google) foreach ($db['data'] as $u) if ($google['id'] == $u['google']['id']) return ["status" => 403, "error" => "Access Denied", "reason" => "Google account is already associated with an account"];
        if ($facebook) foreach ($db['data'] as $u) if ($facebook['id'] == $u['facebook']['id']) return ["status" => 403, "error" => "Access Denied", "reason" => "Facebook account is already associated with an account"];

        exit();

        $id = rand($intval(str_repeat('1', 16)), $intval(str_repeat('9', 16)));
        while (isset($db['idR'][strval($id)])) $id = rand($intval(str_repeat('1', 16)), $intval(str_repeat('9', 16)));
        var_dump($id);
        exit();
        $db['idR'][strval($id)] = $email;
        $db['unr'][strval($id)] = $email;
        $db['data'][$email] = ['id' => strval($id), 'time' => time(), 'icon' => '', 'products' => [], 'reviews' => [], 'ip' => [], 'emailVerified' => (($google)||($facebook)||($vk)||($github)||($discord)||($twitter))];
        if (isset($password)) $db['data'][$email]['password'] = password_hash($password, PASSWORD_DEFAULT);
        if (isset($data['googleID'])) { $db['data'][$email]['googleID'] = $data['googleID']; $db['data'][$email]['googleEmail'] = $email; }
        if (isset($data['facebookID'])) { $db['data'][$email]['facebookID'] = $data['facebookID']; $db['data'][$email]['facebookEmail']; }

        exit();

        //return login($data);
    }else return ["status" => 400, "error" => "Access Denied", "reason" => "Missing Information"];
}

function login($ip, $mysqli, $username = '', $password = '', $google = '', $facebook = '', $vk = '', $github = '', $discord = '', $twitter = '') {
    $ip = mysqli_real_escape_string($mysqli, $ip);
    if ((($username))&&(($password)||($google)||($facebook)||($vk)||($github)||($discord)||($twitter))) {
        $accountRow = [];
        if ($username) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `username` = '".mysqli_real_escape_string($mysqli, $username)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }elseif ($google) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `google_identifier` = '".mysqli_real_escape_string($mysqli, $google)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }elseif ($facebook) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `facebook_identifier` = '".mysqli_real_escape_string($mysqli, $facebook)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }elseif ($vk) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `vk_identifier` = '".mysqli_real_escape_string($mysqli, $vk)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }elseif ($github) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `github_identifier` = '".mysqli_real_escape_string($mysqli, $github)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }elseif ($discord) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `discord_identifier` = '".mysqli_real_escape_string($mysqli, $discord)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }elseif ($twitter) {
            $query = $mysqli->query("SELECT * FROM `user_data` WHERE `twitter_identifier` = '".mysqli_real_escape_string($mysqli, $twitter)."'");
            if ($query) while ($row = mysqli_fetch_assoc($query)) $accountRow = $row;
        }else return ["status" => 500, "error" => "Server Error", "reason" => "An unexpected error occured"];

        if (!($accountRow)) return ["status" => 403, "error" => "Access Denied", "reason" => "Incorrect Information"];

        $passwordHash = mysqli_real_escape_string($mysqli, $accountRow['password']);
        $accountID = mysqli_real_escape_string($mysqli, $accountRow['id']);

        //check if the password is incorrect
        if ((!(($google)||($facebook)||($vk)||($github)||($discord)||($twitter)))&&(!(password_verify($password, $passwordHash)))) return ["status" => 403, "error" => "Access Denied", "reason" => "Incorrect Information"];

        $mysqli->query("DELETE FROM `sessions_data` WHERE `account_id` = '$accountID'");

        $mysqli->query("INSERT INTO `sessions_data` (`session_id`, `time`, `time_expire`, `ip`, `account_id`) VALUES ('".mysqli_real_escape_string($mysqli, $_SESSION['id'])."', '".time()."', '".strval(time() + 1800)."', '$ip', '$accountID')");

        $arr = [];
        if (json_decode($accountRow['ips'], 1)) $arr = json_decode($accountRow['ips'], 1);
        $arr[time()] = $ip;
        while (count($arr) > 5) unset($arr[array_keys($arr)[0]]);
        $arr = mysqli_real_escape_string($mysqli, json_encode($arr, 64|256));

        $mysqli->query("UPDATE `user_data` SET `ip` = '$ip', `ips` = '$arr' WHERE `id` = '$accountID';");

        return ["status" => 200, "data" => $accountRow];
    }else{
        return ["status" => 400, "error" => "Access Denied", "reason" => "Missing Information"];
    }
}

function logout($mysqli) {
    $mysqli->query("DELETE FROM `sessions_data` WHERE `session_id` = '".mysqli_real_escape_string($mysqli, $_SESSION['id'])."'");
    return ["status" => 200];
}

$x = checkLogin($ip, $mysqli);
$loggedIn = $x['status'] == 200;

$stripeConfig = [
	'secret' => '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',
	'publishable' => 'pk_live_51IAG8LDxynzWhOlyHXps9XTwnolhxNks1ADYfq3So4wd9sLNPqfdrLR8ZzNTHlzbtcJbqHrVUmoRzLLSYeUWh6Y300k9xg8U1W',
	'webhook' => 'whsec_VrWdZhzl5p5zOLrfLvmvnkjLzVzFwZdc',
	'prices' => ['price_1IAIOuDxynzWhOlywTJOgAOk', 'price_1IAZ4wDxynzWhOlyyAZQql9V', 'price_1IAZ5lDxynzWhOlypObGIz54', 'price_1IAZ6DDxynzWhOlyBZ8F0d3D', 'price_1IAZ6eDxynzWhOlyem2uG7Np', 'price_1IF4deDxynzWhOlylkCMWfx8']
];

?>
