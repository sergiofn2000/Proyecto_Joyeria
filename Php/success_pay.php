<?php
session_start();
require './handlers/db_handler.php';
$conex = new db_handler();
require '../vendor/autoload.php';

if (isset($_SESSION['id']) && $_GET['data'] != "null") {
  try {

    $stripe = new \Stripe\StripeClient('sk_test_51NEURIJrKov9XhhgFe5qTk6EQFV8lICd8QtvKMJm0p9jaWSlHw1HhLcrXvwcK9HlgotmePkRtB0vqjmcYfOjrUpn00B1GdzkhN');
    $session = $stripe->checkout->sessions->retrieve($_GET['data'], []);
    $result2 = $conex->execute("INSERT INTO pedido(id_usuario,num_pedido) 
       VALUES (?, ?)", "is", [$_SESSION['id'], $session->payment_intent]);
       if($result2){
   $result = $conex->execute("DELETE FROM carrito WHERE id_usuario=?", "i", [$_SESSION['id']]);
       }
      $response = array(
        'status' => 'success',
        'message1' => 'Gracias por su compra, ' . $session->customer_details->name . 'su numero de pedido es' . $session->payment_intent,
        'message' => $_SESSION['carrito_stripe'],
      );
      die(json_encode($response));
    
  } catch (Error $e) {
    $response = array(
      'status' => 'error',
      'message' => 'pedido no pagado ',
      'message' => $result2,

    );
    die(json_encode($response));
  }
}
