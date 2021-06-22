<?php

if (isset($_GET['code'])) {
    $curl = curl_init();
    curl_setopt_array($curl, [CURLOPT_URL => 'https://discord.com/api/v6/oauth2/token', CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"], CURLOPT_POSTFIELDS => 'client_id=530470790190071810&client_secret=twFGvo3A62pdtwZcJKKxjG4dXeotQRV2&code='.$_GET['code'].'&grant_type=authorization_code&scope=identify%20email%20connections&redirect_uri=https%3A%2F%2Fauth.czechify.com%2Fdiscord%2F', CURLOPT_SSL_VERIFYHOST => false, CURLOPT_SSL_VERIFYPEER => false]);
    $resp = json_decode(curl_exec($curl), 1);
    curl_close($curl);
    var_dump($resp);
    if (isset($resp['error'])) { header("Location: https://discord.com/oauth2/authorize?client_id=530470790190071810&redirect_uri=https%3A%2F%2Fauth.czechify.com%2Fdiscord%2F&response_type=code&scope=identify%20email%20connections"); exit(); }
}







?>
