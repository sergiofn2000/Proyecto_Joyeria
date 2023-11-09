<?php

use WpOrg\Requests\Response;

require_once('../vendor/autoload.php');

\Stripe\Stripe::setApiKey('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');

// Recibir los datos de los productos desde JavaScript
$productos = $_POST['productos'];

$lineItems = [];
foreach ($productos as $producto) {
  $lineItems[] = [
    'name' => $producto['name'],
    'description' => $producto['description'],
    'amount' => $producto['amount'],
    'currency' => $producto['currency'],
    'quantity' => $producto['quantity'],
  ];
}

// $items ="";
// foreach ($productos as $fila) {

//    $items = [
//     'name' => $fila['name'],
//     'description' => $fila['description'],
//     'amount' => $fila['amount'],
//     'currency' => $fila['currency'],
//     'quantity' => $fila['quantity'],
//   ];
// }

// $response = array(
//   'foreach' => $items,

// );
// die(json_encode($response));

$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => $lineItems,
  'mode' => 'payment',
  'success_url' => 'stripe-exito?session_id={CHECKOUT_SESSION_ID}',
  'cancel_url' => 'stripe-cancelar',
]);

// Enviar la respuesta a JavaScript
$response = [
  'sessionUrl' => $session->url,
  
];
echo json_encode($response);
?>
