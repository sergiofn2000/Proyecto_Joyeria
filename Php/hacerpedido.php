<?php
    session_start();
    require_once('./handlers/db_handler.php');
    

    $array = array();
    $array = json_encode($_POST['data']);
    if(empty($_POST)){
        $response = array(
            'status' => 'error',
            'username' => $_SESSION['nombre'],
            'pedido' => false,
        );
    }
    else{
    $conex = new db_handler();
    $result1 =$conex->execute("INSERT INTO pedido(id_usuario, producto) 
                    VALUES (?, ?)", "is", [$_SESSION['id'], $array]);

    // $result =$conex->execute("INSERT INTO pedido(id_usuario, producto) 
    //                 VALUES (?, ?)", "is", [$_SESSION['id'], $array]);

    // mysqli_query($conexion, "INSERT INTO pedido(id_usuario, producto) 
    // VALUES ('$_SESSION[id]', '$array')");
    $result2=$conex->execute("DELETE FROM carrito WHERE id_usuario=?", "i", [$_SESSION['id']]);
    
    if($result1 && $result2){
    $response = array(
        'status' => 'success',
        'username' => $_SESSION['nombre'],
        'pedido' => $result1,
    );
    }
    else{
        $response = array(
            'status' => 'error',
            'username' => $_SESSION['nombre'],
            'pedido' => $result1,
        );
    }
}

    die(json_encode($response));


?>