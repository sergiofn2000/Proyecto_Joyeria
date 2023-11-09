<?php
session_start();
require_once('./handlers/db_handler.php');


$array = array();
$array = json_encode($_POST);

if (empty($_POST)) {
    $response = array(
        'status' => 'error',
        'username' => $_SESSION['nombre'],
        'pedido' => false,
    );
} else {
    $conex = new db_handler();
    $select = $conex->query("select carrito.cantidad
    from productos,carrito,registros_user
    where registros_user.id=carrito.id_usuario AND productos.id=carrito.id_producto AND carrito.id_usuario=? AND productos.id=?", "is", [$_SESSION['id'],$_POST['data']]);
    if(empty($select)){
        echo "hola";
    }
    foreach ($select as $fila) {
        if($fila['cantidad'] >=2 ){
            $result1 = $conex->execute("UPDATE `carrito`, `productos` SET `carrito`.`cantidad`=`carrito`.`cantidad`-(1) WHERE `carrito`.`id_producto`=`productos`.`id` AND `productos`.`id`=? AND `carrito`.`id_usuario`=?", "si", [$_POST['data'] , $_SESSION['id']]);
            if ($result1 == true) {
                $response = array(
                    'status' => 'success',
                    'username' => $_SESSION['nombre'],
                );
                die(json_encode($response));
            } else {
                $response = array(
                    'status' => 'error',
                    'username' => $_SESSION['nombre'],
                );
                die(json_encode($response));
            }
        }
        else{
            $result = $conex->execute("DELETE FROM carrito WHERE id_usuario=? AND id_producto=(SELECT id 
                                                                               FROM productos
                                                                               WHERE productos.id=?)", "is", [$_SESSION['id'], $_POST['data']]);
        $response = array(
        'status' => 'error',
        'username' => $_SESSION['nombre'],
        );
        die(json_encode($response));
        }

    }

}




?>