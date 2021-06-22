<?php

require '../'.$ref.'vendor/autoload.php';

//header('Content-Type: application/json');

$config = [
	'stripe_secret_key' => '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',
	'stripe_publishable_key' => 'pk_live_51IAG8LDxynzWhOlyHXps9XTwnolhxNks1ADYfq3So4wd9sLNPqfdrLR8ZzNTHlzbtcJbqHrVUmoRzLLSYeUWh6Y300k9xg8U1W',
	'stripe_webhook_secret' => 'whsec_VrWdZhzl5p5zOLrfLvmvnkjLzVzFwZdc',
	'domain' => 'https://czechify.com/',
	'price_1' => 'price_1IAIOuDxynzWhOlywTJOgAOk',
	'price_2' => 'price_1IAZ4wDxynzWhOlyyAZQql9V',
	'price_3' => 'price_1IAZ5lDxynzWhOlypObGIz54',
	'price_4' => 'price_1IAZ6DDxynzWhOlyBZ8F0d3D',
	'price_5' => 'price_1IAZ6eDxynzWhOlyem2uG7Np',
	'price_6' => 'price_1IF4deDxynzWhOlylkCMWfx8'
];

// Make sure the configuration file is good.
if (!$config) {
	http_response_code(500);
	echo json_encode([ 'error' => 'Internal server error.' ]);
	exit;
}

\Stripe\Stripe::setApiKey($config['stripe_secret_key']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$input = file_get_contents('php://input');
	$body = json_decode($input);
}

if (json_last_error() !== JSON_ERROR_NONE) {
	http_response_code(400);
	echo json_encode([ 'error' => 'Invalid request.' ]);
	exit;
}
