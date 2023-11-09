<?php
session_start();
require_once '../vendor/autoload.php';
require_once './handlers/db_handler.php';
require_once './handlers/mail_handler.php';


if (isset($_POST) && isset($_POST['type']) && $_POST['type'] == 'validatePayment') {
   $stripe = new \Stripe\StripeClient('sk_test_CGGvfNiIPwLXiDwaOfZ3oX6Y'); // Test


  $session = $stripe -> checkout -> sessions -> retrieve($_POST['data']['session_id'], []);
  $invoice = $stripe -> invoices -> retrieve($session -> invoice, []);
  
  if ($session -> status != 'complete') {
    $response = array(
      "status" => 'error',
      "message" => 'Se ha producido un error durante el pago.'
    );
    die(json_encode($response));
  }
  
  if (empty($_SESSION['id'])) {
    $response = array(
      "status" => 'error',
      "message" => 'Se ha producido un error. Pongase en contacto con nosotros para validar su pago.'
    );
    die(json_encode($response));
  }

  $conn = new db_handler();
  $result = $conn -> query("SELECT * FROM pedidos WHERE url = ?", 's', [$invoice -> hosted_invoice_url]);

  if (empty($result[0])) {
    $conn -> execute("INSERT INTO pedidos (user, date, total, url) VALUES (?, ?, ?, ?)", 'isis', [$_SESSION['id'], date('Y-m-d H:i:s'), $session -> amount_total, $invoice -> hosted_invoice_url]);
  }
  



  $conn->execute("DELETE FROM carrito WHERE id_usuario=?", "i", [$_SESSION['id']]);
  $response = array(
    "status" => 'success',
    "message" => 'Se ha realizado el pedido de forma exitosa.'
  );
  die(json_encode($response));
}


try{

$shipping_id = '';

// if ($_SESSION['free_shipping']) {
    $shipping_id = 'pk_test_Dt4ZBItXSZT1EzmOd8yCxonL'; // Test
   
// } else {
    $shipping_id = 'pk_test_Dt4ZBItXSZT1EzmOd8yCxonL'; // Test
  
// }

   \Stripe\Stripe::setApiKey('sk_test_CGGvfNiIPwLXiDwaOfZ3oX6Y'); // Test

header('Content-Type: application/json');

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => $_SESSION['products_pasarela'],
  // 'shipping_options' => [['shipping_rate' => $shipping_id]],
  'invoice_creation' => ['enabled' => true],
  'phone_number_collection' => [
    'enabled' => true,
  ],
  'tax_id_collection' => [
    'enabled' => true,
  ],
  'mode' => 'payment',
  'allow_promotion_codes' => true,
  'success_url' => 'http://joyeria.local.com/proceedpayment/{CHECKOUT_SESSION_ID}',
  'cancel_url' => 'http://joyeria.local.com/cart',
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
      'message' => 'Envíos disponibles únicamente a la Península Ibérica e Islas Baleares.',
    ],
  ],
]);

}
catch(Exception $e) {  
  $api_error = $e->getMessage();  
} 
if(empty($api_error) && $checkout_session){ 
  $_SESSION['stripe'] = [$checkout_session->id,$checkout_session->url,$checkout_session->payment_status];
  

  $response = array( 
      'status' => 'success',
      'url' => $checkout_session->url,
      'sessionId' => $_SESSION['carrito_stripe']
  ); 
  die(json_encode($response));
}else{ 
  $response = array( 
      'status' => 'error1', 
      'error' => array( 
      'message' => 'Checkout Session creation failed! '.$api_error    
      ) 
  ); 
  die(json_encode($response));
} 
?>



