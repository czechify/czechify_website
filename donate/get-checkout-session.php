<?php

require_once 'shared.php';

$id = $_GET['sessionId'];
$checkout_session = \Stripe\Checkout\Session::retrieve($id);

$content = json_decode(file_get_contents('./log.json'), 1);

array_push($content, json_decode(json_encode($checkout_session), 1));

file_put_contents('./log.json', json_encode($content));



//var_dump($checkout_session);

//echo json_encode($checkout_session);
