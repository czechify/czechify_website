<?php

if (!(isset($_GET['priceId']))) exit();

require_once 'shared.php';

$domain_url = $config['domain'];

$checkout_session = \Stripe\Checkout\Session::create([
	'success_url' => 'https://czechify.com/donate/success.html?session_id={CHECKOUT_SESSION_ID}',
	'cancel_url' => 'https://czechify.com/donate/canceled.html',
	'payment_method_types' => ['card'],
	'mode' => 'subscription',
	'line_items' => [[
	  	'price' => $_GET['priceId'],
	  	'quantity' => 1,
  	]]
]);

$sessions = json_decode(file_get_contents('checkout-sessions.json'), 1);
$sessions[$checkout_session['id']] = $_GET['priceId'];
file_put_contents('checkout-sessions.json', json_encode($sessions, 64|256));

echo json_encode(['sessionId' => $checkout_session['id']]);
