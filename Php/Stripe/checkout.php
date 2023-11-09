<?php
session_start();
require_once '../../vendor/autoload.php';



\Stripe\Stripe::setApiKey('sk_test_51NEURIJrKov9XhhgFe5qTk6EQFV8lICd8QtvKMJm0p9jaWSlHw1HhLcrXvwcK9HlgotmePkRtB0vqjmcYfOjrUpn00B1GdzkhN');
header('Content-Type: application/json');

$YOUR_DOMAIN = 'joyeria.local.com/';

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => $_SESSION['products_pasarela'], 
  'mode' => 'payment',
  'success_url' => 'joyeria.local.com/success.html',
  'cancel_url' => 'joyeria.local.com/cancel.html',
  'automatic_tax' => [
    'enabled' => true,
  ],
  "payment_method_types"=> [
    "card",
    "paypal",

  ],
  'shipping_address_collection' => ['allowed_countries' => ['ES']],
  'custom_text' => [
    'shipping_address' => [
      'message' => 'El Envio tardara entre 1-3 dias hábiles',
    ],
    'submit' => ['message' => 'We\'ll email you instructions on how to get started.'],
  ]
]);
$_SESSION['stripe'] = $checkout_session;

$response = [
  'status' => 'success',
  'url' => $checkout_session->url,
];

echo json_encode($response);