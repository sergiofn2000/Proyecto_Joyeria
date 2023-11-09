<?php
session_start();
require_once('./handlers/db_handler.php');
$conex = new db_handler();

if (isset($_SESSION['id'])) {
    $result = $conex->execute("DELETE FROM carrito WHERE id_usuario=? AND id_producto=(SELECT id 
                                                                                   FROM productos
                                                                                   WHERE productos.id=?)", "is", [$_SESSION['id'], $_POST['data']]);
} elseif (isset($_COOKIE['cart'])) {
    $carrito_cookie = json_decode($_COOKIE['cart'], true);
    foreach ($carrito_cookie as $key => $item) {
        if ($item['id_producto'] == $_POST['data']) {
            unset($carrito_cookie[$key]);
            setcookie('cart', json_encode($carrito_cookie), time() + (86400 * 30), '/');
            break;
        }
    }
}
 else {
    $response = array(
        'status' => 'error',
        'message' => 'No se puede eliminar el producto del carrito',
    );
    die(json_encode($response));
}

$response = array(
    'status' => 'success',
    'message' => 'Producto eliminado',
);
die(json_encode($response));
?>
