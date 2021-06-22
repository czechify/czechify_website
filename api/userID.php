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
    $curl = curl_init();
    foreach ($_SERVER['errors'] as $error) {
        curl_setopt_array($curl, [CURLOPT_URL => "https://ptb.discord.com/api/webhooks/816267936238927892/oLqLA9WcUbXXEvmOdfX4Q1pbgyQai1dY-KJ9E-LV_cBnXi67PfExEU6JksBs6h3-IwBc", CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ["Content-Type: application/json"], CURLOPT_POSTFIELDS => json_encode(['embeds' => [['title' => 'Error Reporting', 'description' => json_encode($error, 64|256)]]])]);
        $resp = curl_exec($curl);
    }
    curl_close($curl);
    die(json_encode(['error' => ['code' => [http_response_code($code), http_response_code()][1], 'message' => $_SERVER['errors'][count($_SERVER['errors']) - 1]['message'], 'errors' => $_SERVER['errors'], 'status' => strtoupper(str_replace(' ', '_', $_SERVER['codeToText']($code)))]], $_SERVER['flags']));
}

function returnSuccess($code, $a = '', $b = '', $c = '') {
    die(json_encode(['success' => ['code' => [http_response_code($code), http_response_code()][1], 'message' => $_SERVER['output'][count($_SERVER['output']) - 1]['message'], 'output' => $_SERVER['output'], 'status' => strtoupper(str_replace(' ', '_', $_SERVER['codeToText']($code)))]], $_SERVER['flags']));
}

file_put_contents("./log.json", json_encode(array_merge(json_decode(file_get_contents("./log.json"), 1), [$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']]), 128));

$defaultFlags = 64|128|256;

$varsToCollect = ['userID', 'userIDType'];

foreach ($_GET as $a => $b) if (in_array($a, $varsToCollect)) eval("$$a = \$_GET['$a'];");

if (isset($prettyPrint)) $_SERVER['flags'] = 64|128|256; else $_SERVER['flags'] = $defaultFlags;

if (isset($redirect)) {
    $logFile = '../../apiAccessFile.json';
    if (!(file_exists($logFile))) file_put_contents($logFile, '[]');
    $logData = json_decode(file_get_contents($logFile), 1);
    $logData[] = [dirname($_SERVER['PHP_SELF']), $redirect, $_GET, time()];
    file_put_contents($logFile, json_encode($logData, 64|128|256));
}

if (!(isset($_SERVER['HTTP_AUTHORIZATION']))) $_SERVER['HTTP_AUTHORIZATION'] = 'martin';

if (!(isset($_SERVER['HTTP_AUTHORIZATION']))) returnErrors(401, addToErrors(['message' => 'The request is missing a valid Authentication Token.', 'domain' => 'global', 'reason' => 'Forbidden']));

$query = $mysqli->query("SELECT `token`, `granted_to` FROM `api_authentication` WHERE `resolver_access` = 1 AND `token` = '".$mysqli->escape_string($_SERVER['HTTP_AUTHORIZATION'])."'");
if ($query) while ($row = $query->fetch_assoc()) { $auth = $mysqli->escape_string($row['token']); $_SERVER['HTTP_AUTHORIZATION'] = $row['granted_to']; }
if (!(isset($auth))) returnErrors(401, addToErrors(['message' => 'The request is missing a valid Authentication Token.', 'domain' => 'global', 'reason' => 'Forbidden']));

$curl = curl_init();
curl_setopt_array($curl, [CURLOPT_URL => "https://ptb.discord.com/api/webhooks/816267272867676180/WKtsF8iLcnK0I8qlqyFqzSyTopp9uTlaEiHJNafxL-Jr0_NWTDy0J21Q7huwwI-4Pl5X", CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ["Content-Type: application/json"], CURLOPT_POSTFIELDS => json_encode(['embeds' => [['title' => 'Access Log', 'description' => $_SERVER['HTTP_AUTHORIZATION'].' has accessed userID api: '.$_SERVER['REQUEST_URI']]]])]);
$resp = curl_exec($curl);
curl_close($curl);

if (!(isset($userID))) returnErrors(400, addToErrors(['message' => 'The request is missing a valid user id.', 'domain' => 'global', 'reason' => 'Bad Request']));

if (!(isset($userIDType))) returnErrors(400, addToErrors(['message' => 'The request is missing a valid user id type.', 'domain' => 'global', 'reason' => 'Bad Request']));

if ($userIDType == 'discord') {
    $userID = $mysqli->escape_string($userID);
    $query = $mysqli->query("SELECT `id`, `deactivated` FROM `user_data` WHERE `discord_identifier` = '$userID'");
    if ($query) while ($row = $query->fetch_assoc()) if ($row['deactivated']) returnErrors(400, addToErrors(['message' => 'Error RUETLP53', 'domain' => 'global', 'reason' => 'Account deactivated'])); else returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $row['id']])); else returnErrors(500, addToErrors(['message' => 'Error 3NFX9EA8', 'domain' => 'global', 'reason' => 'Interal Server Error']));
    $query = $mysqli->query("SELECT `id`, `deactivated` FROM `tmp_user_data` WHERE `discord_identifier` = '$userID'");
    if ($query) while ($row = $query->fetch_assoc()) if ($row['deactivated']) returnErrors(400, addToErrors(['message' => 'Error V5A5N5WC', 'domain' => 'global', 'reason' => 'Account deactivated'])); else returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $row['id']])); else returnErrors(500, addToErrors(['message' => 'Error LYC6Q25L', 'domain' => 'global', 'reason' => 'Interal Server Error']));
    $id = $mysqli->escape_string(bin2hex(random_bytes(16)));
    $username = $mysqli->escape_string(bin2hex(random_bytes(6)));
    $time = $mysqli->escape_string(time());
    $query = $mysqli->query("INSERT INTO `tmp_user_data` (`id`, `username`, `discord_identifier`, `words_data`, `stats_data`, `thanks_data`, `time_created`, `deactivated`) VALUES ('$id', 'TMP_$username', '$userID', '{}', '{}', '{}', '$time', '0')");
    if ($query) returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $id])); else returnErrors(500, addToErrors(['message' => 'Error BKDA3232', 'domain' => 'global', 'reason' => 'Interal Server Error']));
}elseif ($userIDType == 'minecraft') {
    $userID = $mysqli->escape_string($userID);
    $query = $mysqli->query("SELECT `id`, `deactivated` FROM `user_data` WHERE `minecraft_identifier` = '$userID'");
    if ($query) while ($row = $query->fetch_assoc()) if ($row['deactivated']) returnErrors(400, addToErrors(['message' => 'Error UYBHH3PC', 'domain' => 'global', 'reason' => 'Account deactivated'])); else returnSuccess(200, addToOutput(['message' => 'Account found.', 'domain' => 'global', 'Account ID' => $row['id']])); else returnErrors(500, addToErrors(['message' => 'Error BBK4UHE4', 'domain' => 'global', 'reason' => 'Interal Server Error']));
    returnErrors(400, addToErrors(['message' => 'Error 6UTDW3SD', 'domain' => 'global', 'reason' => 'Account not found']));
}

returnErrors(400, addToErrors(['message' => 'The request is missing a user id type.', 'domain' => 'global', 'reason' => 'Bad Request']));

?>
