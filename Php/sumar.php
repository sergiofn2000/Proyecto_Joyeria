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
        $result1 = $conex->execute("UPDATE `carrito`, `productos` SET `carrito`.`cantidad`=`carrito`.`cantidad`+(1) WHERE `carrito`.`id_producto`=`productos`.`id` AND `productos`.`id`=? AND `carrito`.`id_usuario`=?", "si", [$_POST['data'] , $_SESSION['id']]);

        if ($result1) {
            $response = array(
                'status' => 'success',
                'Carrito' => 'Sumado',
               
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status' => 'error',
            );
            die(json_encode($response));
        }
    }




?>