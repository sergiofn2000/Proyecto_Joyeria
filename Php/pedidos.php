<?php
    session_start();
    require_once('./handlers/db_handler.php');
    $conex = new db_handler();
    if (!empty($_SESSION['id'])) {
        $array=[];
        $result1 = $conex->query("select pedido.num_pedido
                                    from pedido,registros_user
                                    where registros_user.id=pedido.id_usuario AND pedido.id_usuario=?", "i", [$_SESSION['id']]);
        foreach ($result1 as $fila) {
            array_push($array, array(
                "nombre_pedido" => $fila['num_pedido'],
                
            ));
        }
        $response = array(
            'status' => 'success',
            'pedidos' => $array,
        );
        die(json_encode($response));
    } else {
        $response = array(
            'status' => 'error Session',
            'mensage' => "No tienes pedidos",

        );
        die(json_encode($response));
    }
?>