<?php
    session_start();
    require_once('./handlers/price_handler.php');
    require_once('./handlers/db_handler.php');
    
    $conex = new db_handler();


   
    if (isset($_SESSION['id'])) {
        try {
            $result = $conex->query("select productos.descripcion,carrito.cantidad,productos.precio_venta 
                                   from productos,carrito,registros_user
                                   where registros_user.id=carrito.id_usuario AND productos.id=carrito.id_producto AND carrito.id_usuario=?", "i", [$_SESSION['id']]);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage();
        }
        if (empty($result)) {
            
            $response = array(
                'status' => 'error',
                'message' => 'USUARIO NO TIENE NADA EN EL CARRITO ',
                'SESSION' => $_SESSION,
            );
            die(json_encode($response));

           
        } else {
            try {
                $result1 = $conex->query("select productos.descripcion,carrito.cantidad,carrito.variante,productos.precio_venta,productos.id,productos.imagen_ftp
                                       from productos,carrito,registros_user
                                       where registros_user.id=carrito.id_usuario AND productos.id=carrito.id_producto AND carrito.id_usuario=?", "i", [$_SESSION['id']]);
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage();
            }

            $total = 0;
            $products = [];
            $products_pasarela = [];

            foreach ($result1 as $fila) {
                $fila['precio_venta_sin_iva'] = get_cost_without_iva($fila['precio_venta']);
                $fila['precio_venta_sin_iva'] = number_format($fila['precio_venta_sin_iva'], 2);

                $fila['precio_venta'] = get_cost($fila['precio_venta']);
                $fila['precio_venta'] = number_format($fila['precio_venta'], 2);

                $total = $total + $fila['precio_venta'];

                array_push($products, array(
                    'nombre' => $fila['descripcion'],
                    'id' => $fila['id'],
                    'cantidad' => $fila['cantidad'],
                    'ref' => $fila['variante'],
                    'precio' => $fila['precio_venta'],
                    'imagen' => $fila['imagen_ftp'],
                ));
                
                array_push($products_pasarela, array(
                    'quantity' => $fila['cantidad'],
                    'price_data' => [
                        'unit_amount' => $fila['precio_venta_sin_iva']*100,
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $fila['descripcion'] . ' (' . $fila['variante'] . ')',
                            'tax_code' => 'txcd_30060007',
                            'images' => [
                                'https://joyeria.local.com/catalogo/'.$fila['imagen_ftp']
                            ],
                        ],
                    ],
                ));
               
            }
            if ($total >= 300) {
                $_SESSION['free_shipping'] = true;
            } else {
                $_SESSION['free_shipping'] = false;
            }
            $_SESSION['products_pasarela']=$products_pasarela;
            $_SESSION['carrito_stripe']=json_encode($products);
            $response = array(
                'status' => 'success',
                'username' => $_SESSION['nombre'],
                'productos' => $products,
                'Session' => $_SESSION,
                'productos_pasarela' =>$products_pasarela ,
                'sesion_stripe'=> $_SESSION['stripe'],
            );

            die(json_encode($response));

            
        }
    } // Si no hay sesión iniciada
    elseif (isset($_COOKIE['cart'])) {
        $carrito_cookie = json_decode($_COOKIE['cart'], true);
        if (empty($carrito_cookie)) {
            // El arreglo de la cookie está vacío
            $response = array(
                'status' => 'error',
                'message' => 'El carrito está vacío',
            );
            die(json_encode($response));
        }
        $total = 0;
        $products = [];
        $products_pasarela = [];

        foreach ($carrito_cookie as $item) {
            if (isset($item['id_producto'])){
            $id_producto = $item['id_producto'];
            $cantidad = $item['cantidad'];
            $variante = $item['variante'];

            $result = $conex->query("SELECT descripcion, precio_venta, imagen_ftp FROM productos WHERE id=?", "i", [$id_producto]);

            foreach ($result as $fila) {
                $precio_venta_sin_iva = get_cost_without_iva($fila['precio_venta']);
                $precio_venta_sin_iva = number_format($precio_venta_sin_iva, 2);

                $precio_venta = get_cost($fila['precio_venta']);
                $precio_venta = number_format($precio_venta, 2);

                $total += $precio_venta * $cantidad;

                array_push($products, array(
                    'nombre' => $fila['descripcion'],
                    'id' => $id_producto,
                    'cantidad' => $cantidad,
                    'ref' => $variante,
                    'precio' => $precio_venta,
                    'imagen' => $fila['imagen_ftp'],
                ));

                array_push($products_pasarela, array(
                    'quantity' => $cantidad,
                    'price_data' => [
                        'unit_amount' => $precio_venta_sin_iva * 100,
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $fila['descripcion'] . ' (' . $variante . ')',
                            'tax_code' => 'txcd_30060007',
                            'images' => [
                                'https://joyeria.local.com/catalogo/' . $fila['imagen_ftp']
                            ],
                        ],
                    ],
                ));
            }
        }
        }

        if ($total >= 300) {
            $_SESSION['free_shipping'] = true;
        } else {
            $_SESSION['free_shipping'] = false;
        }
        $_SESSION['products_pasarela'] = $products_pasarela;
        $_SESSION['carrito_stripe'] = json_encode($products);
        $response = array(
            'status' => 'success',
            'productos' => $products,
            'cookie' => $carrito_cookie,
        );
        die(json_encode($response));
    }
    else {
        $response = array(
            'status' => 'error',
            'message' => 'No Te has logueado carrito',
            'session' => $_SESSION,
        );
        die(json_encode($response));
    }
?>
