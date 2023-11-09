<?php
    session_start();
    require_once('./handlers/db_handler.php');
    require_once('./handlers/price_handler.php');
    

    $productId = isset($_POST['data']) ? $_POST['data'] : null;

    if (!empty($_SESSION['id'])) {
        $id_producto = 0;
        $precio = 0;
        $result2 = 0;
        $array = array();
        $array = json_encode($_POST);
        $conex = new db_handler();
        $result1 = $conex->query("select carrito.id_producto
                                    from productos,carrito,registros_user
                                    where registros_user.id=carrito.id_usuario AND productos.id=carrito.id_producto AND carrito.id_usuario=? AND productos.id=?", "is", [$_SESSION['id'], $productId]);
        $result3 = $conex->query("select id,precio_venta, ref_variante
                                    from productos
                                    where id=?", "s", [$productId]);
        $id_producto = 0;
        $ref_variante = '';
        $precio = 0;
        $imagen = 0;
        $result2 = 0;
        foreach ($result3 as $fila) {
            $id_producto = $fila['id'];
            $ref_variante = $fila['ref_variante'];
            $fila['precio_venta'] = get_cost($fila['precio_venta']);
            $precio = number_format($fila['precio_venta'], 2);
        }
        if (empty($result1)) {
            $result2 = $conex->execute("INSERT INTO carrito(id_usuario, id_producto,cantidad, precio, variante) 
            VALUES (?, ?,1,?, ?)", "iiis", [$_SESSION['id'], $id_producto, $precio, $ref_variante]);
             
            if ($result2) {
                $response = array(
                    'status' => 'success',
                    'añadir' => $productId,
                );
                die(json_encode($response));
            } else {
                $response = array(
                    'status' => 'error',
                    'datos' => $imagen,

                );
                die(json_encode($response));
            }
        } else {
            $result2 = $conex->execute("UPDATE carrito, productos 
            SET carrito.cantidad = carrito.cantidad + 1 
            WHERE carrito.id_producto = productos.id 
            AND carrito.id_usuario = ? 
            AND productos.id = ?", "ii", [$_SESSION['id'], $productId]);
        }

        if ($result1 && $result2) {
            $response = array(
                'status' => 'success',
                'añadir' => $result1
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status' => 'error2',
                'añadir' => $result2
            );
            die(json_encode($response));
        }
    } elseif (!empty($productId)) {
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : array();

        $conex = new db_handler();
        $result = $conex->query("SELECT id, precio_venta, ref_variante FROM productos WHERE id=?", "s", [$productId]);

        if (!empty($result)) {
            $id_producto = $result[0]['id'];
            $precio = $result[0]['precio_venta'];  
            $ref_variante = $result[0]['ref_variante'];

            if (isset($cart[$productId])) {
                $cart[$productId]['cantidad']++;
            } else {
                $cart[$productId] = array(
                    'id_producto' => $id_producto,
                    'cantidad' => 1,
                    'precio' => $precio,
                    'variante' => $ref_variante
                );
            }

            setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');

            $response = array(
                'status' => 'success',
                'añadir' => $productId,
                'message' => $cart,
                
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'El producto con ID '.$productId.' no existe.',
            );
            die(json_encode($response));
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'No se ha proporcionado el parámetro "data".',
        );
        die(json_encode($response));
    }
?>
