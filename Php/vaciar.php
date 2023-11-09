<?php
session_start();
require_once('./handlers/db_handler.php');
$conex = new db_handler();

if (isset($_SESSION['id'])) {
    $result = $conex->execute("DELETE FROM carrito WHERE id_usuario=?", "i", [$_SESSION['id']]);
    if ($result) {
        $response = array(
            'status' => 'success',
            'vaciar' => $result
        );
    } else {
        $response = array(
            'status' => 'error',
            'vaciar' => $result
        );
    }
} elseif (isset($_COOKIE['cart'])) {
    $response = array(
        'status' => 'error',
        'message' => 'No se puede vaciar el carrito de la sesión actual',
        'vaciar' => false
    );

    // Eliminar la cookie estableciendo su tiempo de vida en el pasado
    setcookie('cart', '', time() - (86400 * 30), '/');
} else {
    $response = array(
        'status' => 'error',
        'message' => 'No hay sesión ni carrito en la cookie',
        'vaciar' => false
    );
}

die(json_encode($response));

?>