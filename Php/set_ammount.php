<?php
session_start();
require_once('./handlers/db_handler.php');

if (empty($_POST)) {
    $response = array(
        'status' => 'error',
        'username' => $_SESSION['nombre'],
        'pedido' => false,
    );
} else {
    $conex = new db_handler();
    
    // Verificar si existe la cookie 'cart' y obtener su valor
    $cartData = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : array();
    
    $productId = $_POST['data']['id'];
    $quantity = $_POST['data']['ammount'];

    if (!empty($_SESSION['id'])) {
        // Actualizar la cantidad en la base de datos
        $result = $conex->execute("UPDATE `carrito`, `productos` SET `carrito`.`cantidad`=? WHERE `carrito`.`id_producto`=`productos`.`id` AND `productos`.`id`=? AND `carrito`.`id_usuario`=?", "iii", [$quantity, $productId, $_SESSION['id']]);
        
        if ($result) {
            $response = array(
                'status' => 'success',
            );
        } else {
            $response = array(
                'status' => 'error',
            );
        }
    } else if (isset($_COOKIE['cart'])) {
        // Actualizar la cantidad en la cookie 'cart' si existe el producto
        foreach ($cartData as &$item) {
            if ($item['id_producto'] == $productId) {
                $item['cantidad'] = $quantity;
                break;
            }
        }
        
        // Actualizar la cookie 'cart'
        setcookie('cart', json_encode($cartData), time() + (86400 * 30), '/');
        
        $response = array(
            'status' => 'success',
            'message' => $_COOKIE['cart'],

        );
    } else {
        $response = array(
            'status' => 'error',
        );
    }
    
    die(json_encode($response));
}
?>
