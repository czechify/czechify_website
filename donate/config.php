<?php

require_once 'shared.php';

echo json_encode([
  'publishableKey' => $config['stripe_publishable_key'],
  'price_1' => $config['price_1'],
  'price_2' => $config['price_2'],
  'price_3' => $config['price_3'],
  'price_4' => $config['price_4'],
  'price_5' => $config['price_5'],
  'price_6' => $config['price_6']
]);
