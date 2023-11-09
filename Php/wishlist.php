<?php
    session_start();
    require_once('./handlers/db_handler.php');
    $conex = new db_handler();

    // Función para obtener precio

    function get_cost($original_cost) {
        $whith_iva =  ($original_cost)*2.0;
        $final_cost = $whith_iva;
        
        if ($whith_iva < 100) {
            $final_cost = $whith_iva;
        } elseif ($whith_iva < 300) {
            $final_cost = $whith_iva * 0.95;
        } elseif ($whith_iva < 600) {
            $final_cost = $whith_iva * 0.90;
        } elseif ($whith_iva < 1000) {
            $final_cost = $whith_iva * 0.85;
        } elseif ($whith_iva < 1500) {
            $final_cost = $whith_iva * 0.80;
        } else {
            $final_cost = $whith_iva * 0.75;
        }
        $final_cost = $final_cost*1.21;
        return $final_cost;
    }

    // Evalua si hay sesion iniciada
    if (isset($_SESSION['id'])) {
        
           
            $result = $conex->query("select favoritos.id_producto, favoritos.disponible, productos.precio_venta, productos.imagen_ftp,productos.descripcion
                                   from favoritos,productos
                                   where favoritos.id_usuario=? AND productos.id=favoritos.id_producto","i",[$_SESSION['id']]);
        
        if(empty($result)) {
            
            $response = array(
                'status' => 'error',
                'message' => 'USUARIO NO TIENE NADA EN EL CARRITO '
            );
            die(json_encode($response));

           
        }else{ 
            $products_wishlist = [];
            foreach ($result as $fila) {
                $fila['precio_venta'] = get_cost($fila['precio_venta']);
                $fila['precio_venta'] = number_format($fila['precio_venta'], 2);
                array_push($products_wishlist, array(
                'id_producto' => $fila['id_producto'],
                'nombre' => $fila['descripcion'],
                'precio' => $fila['precio_venta'],
                'disponible' => $fila['disponible'],
                'imagen' => $fila['imagen_ftp'],
            ));
            }

            //var_dump($result1);
            $response = array(
                'status' => 'success',
                'message' => 'Exito', 
                'products_wishlist' => $products_wishlist,
                'session' => $_SESSION
            );

            die(json_encode($response));

            // json_encode($result1);
            // var_dump($result1);
            
        }
    } // Si no hay session iniciada
    else {
        $response = array(
            'status' => 'error1',
            'message' => 'No Te has logueado',

        );
        die(json_encode($response));
    }
    ?>

