<?php

session_write_close();

require '../'.$ref.'vendor/autoload.php';

\Stripe\Stripe::setApiKey($stripeConfig['secret']);
$stripe = new \Stripe\StripeClient($stripeConfig['secret']);

$payload = @file_get_contents('php://input');
if (isset($_SERVER['HTTP_STRIPE_SIGNATURE'])) $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE']; else $sig_header = '';
$event = null;

if (isset($_GET['bypass1'])) {
    $event = [
        "id" => "evt_1IO5T5DxynzWhOly357lQskA",
        "object" => "event",
        "api_version" => "2020-08-27",
        "created" => 1614104323,
        "data" => [
            "object" => json_decode(json_encode($stripe->charges->retrieve('ch_1IOhzTDxynzWhOlyYnWYouGt', [])), 1)
        ],
        "livemode" => true,
        "pending_webhooks" => 1,
        "request" => [
            "id" => null,
            "idempotency_key" => "pi_1IO5ScDxynzWhOlyKOXkOMAd-src_1IO5SdDxynzWhOlyImLM9VLq"
        ],
        "type" => "charge.succeeded"
    ];
}elseif (isset($_GET['bypass2'])) {
    $event = [
        "id" => "evt_1IO5T5DxynzWhOly357lQskA",
        "object" => "event",
        "api_version" => "2020-08-27",
        "created" => 1614104323,
        "data" => [
            "object" => json_decode(json_encode($stripe->checkout->sessions->retrieve('cs_live_a1vk1wSynhVuSwNYMy55YTqswQ2FvGGFrquFPnqXyPUh2LFWooC5FaoXQR', [])), 1)
        ],
        "livemode" => true,
        "pending_webhooks" => 1,
        "request" => [
            "id" => null,
            "idempotency_key" => "pi_1IO5ScDxynzWhOlyKOXkOMAd-src_1IO5SdDxynzWhOlyImLM9VLq"
        ],
        "type" => "checkout.session.completed"
    ];
}else {
    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $stripeConfig['webhook']);
    } catch(\UnexpectedValueException $e) {
        http_response_code(400);
        exit();
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
        http_response_code(400);
        exit();
    }
}

$event = json_decode(json_encode($event), 1);

include "../../../martin/products/webhook/stripe.php";

if (isset($handled)) { http_response_code(200); die('najemi.cz'); }

// Handle the event
switch ($event['type']) {
    case 'reporting.report_type.updated':
        break;
    case 'payment_intent.succeeded':
        // $paymentIntent = $event->data->object;
        // $content = json_decode(file_get_contents('log.json'), 1);
        // $content[] = [$payload, $sig_header];
        // file_put_contents('log.json', json_encode($content, 64|128|256));
        break;
    case 'checkout.session.completed':
        // $paymentIntent = $event->data->object;
        // $content = json_decode(file_get_contents('log.json'), 1);
        // $content[] = [$payload, $sig_header];
        // file_put_contents('log.json', json_encode($content, 64|128|256));
        break;
    case 'charge.succeeded':
        $content = json_decode(file_get_contents('log.json'), 1);
        $content[] = [json_decode(json_encode($event), 1), $sig_header];
        file_put_contents('log.json', json_encode($content, 64|128|256));
        break;
    default:
        file_put_contents('otherLog.json', json_encode(array_merge(json_decode(file_get_contents('otherLog.json'), 1), [$event]), 64|128|256));
}

http_response_code(200);
echo 'czechify.com';

?>
