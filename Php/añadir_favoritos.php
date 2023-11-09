<?php
session_start();
require_once('./handlers/db_handler.php');

if (isset($_SESSION['id'])) {
    $id_producto = 0;
    $precio = 0;
    $result2 = 0;
    $array = array();
    $array = json_encode($_POST);
    $conex = new db_handler();
    $result1 = $conex->query("select favoritos.id_producto
                                from productos,favoritos,registros_user
                                where registros_user.id=favoritos.id_usuario AND productos.id=favoritos.id_producto AND favoritos.id_usuario=? AND productos.id=?", "is", [$_SESSION['id'], $_POST['data']]);

    $result3 = $conex->query("select id,precio_venta
                                from productos
                                where id=?", "s", [$_POST['data']]);
    $id_producto = 0;
    $precio = 0;
    $result2 = 0;
    $stock = 0;
    foreach ($result3 as $fila) {
        $id_producto = $fila['id'];
        $precio = $fila['precio_venta'];
    }
    if (empty($result1)) {
        $result4 = $conex->query("select stock
                                from productos
                                where productos.id=?", "i", [$_POST['data']]);
        foreach ($result4 as $fila) {
            $stock = $fila['stock'];
        }

        $result2 = $conex->execute("INSERT INTO favoritos(id_usuario, id_producto,disponible,precio) 
        VALUES (?, ?,?,?)", "iisi", [$_SESSION['id'], $id_producto, $stock, $precio]);
        if ($result2) {
            $response = array(
                'status' => 'success',
                'añadir' => $_POST['data'],
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status' => 'error',

            );
            die(json_encode($response));
        }
    }else{
        $result5 = $conex->execute("DELETE FROM favoritos WHERE id_producto=? AND id_usuario=?","ii",[$_POST['data'],$_SESSION['id'],]);

        if($result5){
        $response = array(
            'status' => 'success',
            'message' => 'Producto eliminado de fav',
            
        );
        die(json_encode($response));
        }else{
            $response = array(
                'status' => 'error',
                'message' => 'Consulta no hecha',
                'resultado' => $result5,
            );
            die(json_encode($response)); 
        }
    }
} else {
        $response = array(
            'status' => 'error-login',
        );
        die(json_encode($response));
}
    