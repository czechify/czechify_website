<?php

require '../vendor/autoload.php';
$config = parse_ini_file('config.ini');

if (!$config) {
	http_response_code(500);
	echo json_encode([ 'error' => 'Internal server error.' ]);
	exit;
}

\Stripe\Stripe::setApiKey($config['stripe_secret_key']);

$input = file_get_contents('php://input');
$body = json_decode($input);

$checkout_session = \Stripe\Checkout\Session::retrieve($body->sessionId);
$stripe_customer_id = $checkout_session->customer;

$return_url = $config['domain'];

$session = \Stripe\BillingPortal\Session::create([
  'customer' => $stripe_customer_id,
  'return_url' => $return_url,
]);

echo json_encode(['url' => $session->url]);
