<?php
session_start();
require_once('./handlers/db_handler.php');
require_once('./handlers/price_handler.php');


$conex = new db_handler();
if(empty($_POST['data'])){
    $response = array(
        'status' => 'error 1',
        'message' => 'No tiene stock'
    );
    die(json_encode($response));
}
if (isset($_POST) && isset($_POST['type']) && $_POST['type'] == 'getVariantId') {
    $result = $conex -> query("SELECT * FROM productos WHERE ref_producto = ? AND variante = ?", 'ss', [$_POST['data']['ref'], $_POST['data']['variante']]);
    $response = array(
        'status' => 'success',
        'data' => $result[0]['id'],
        'producto' => $result[0],
    );
    die(json_encode($response));
}

$result = $conex->query("select id,ref_producto,variante,ref_variante, descripcion,descripcion_larga,precio_venta,atributos,stock,imagen_ftp
                                from productos
                                where productos.id=?", "i", [$_POST['data']]);

$result1 = $conex->query("select id,ref_producto, ref_variante, GROUP_CONCAT(variante SEPARATOR ' ') AS variante,descripcion,descripcion_larga,precio_venta,atributos,stock,imagen_ftp
                                from productos
                                where ref_producto=? 
                                ", "s", [$result[0]['ref_producto']]);

if (!empty($result1)) {
    if (isset($_SESSION['id'])) {
        $result2 = $conex->query("select favoritos.id_producto,productos.id,productos.ref_producto,productos.variante,productos.descripcion,productos.descripcion_larga,productos.precio_venta,productos.atributos,productos.stock,productos.imagen_ftp
                                from favoritos,productos
                                where productos.id=? AND favoritos.id_producto=productos.id AND favoritos.id_usuario=?", "ii", [$_POST['data'], $_SESSION['id']]);
        if (empty($result2)) {

            foreach ($result1 as $fila) {
                if ($fila['stock'] === "Si") {

                    $pattern = '/(\p{L}+):\s*(.*?)\s*(?=(\p{L}+):|$)/u';

                    preg_match_all($pattern, $fila['variante'], $matches, PREG_PATTERN_ORDER);

                    $all_variants = array();

                    $result1[0]['precio_venta'] = get_cost($result[0]['precio_venta']);
                    $result1[0]['precio_venta'] = number_format($result1[0]['precio_venta'], 2);

                    foreach ($matches[1] as $key => $value) {
                        $titulo = $matches[1][$key];
                        $valor = $matches[2][$key];


                        if (!isset($all_variants[$titulo])) {
                            $all_variants[$titulo] = array();
                        }

                        $all_variants[$titulo][] = $valor;
                        $all_variants[$titulo] = array_unique($all_variants[$titulo]);
                    }

                    preg_match_all($pattern, $result[0]['variante'], $matches_this, PREG_PATTERN_ORDER);

                    $this_variant = array();

                    foreach ($matches_this[1] as $key => $value) {
                        $titulo = $matches_this[1][$key];
                        $valor = $matches_this[2][$key];


                        if (!isset($this_variant[$titulo])) {
                            $this_variant[$titulo] = $valor;
                        }
                    }


                    $result1[0]['atributos'] = $all_variants;
                    $response = array(
                        'variant' => $this_variant,
                        'status' => 'success',
                        'producto' => $result1[0],
                        'producto2' => $result[0],
                    );
                    die(json_encode($response));
                } else {
                    $response = array(
                        'status' => 'error 1',
                        'message' => 'No tiene stock'
                    );
                    die(json_encode($response));
                }
            }
        } else {
            foreach ($result1 as $fila) {
                if ($fila['stock'] === "Si") {

                    $pattern = '/(\p{L}+):\s*(.*?)\s*(?=(\p{L}+):|$)/u';

                    preg_match_all($pattern, $fila['variante'], $matches, PREG_PATTERN_ORDER);

                    $all_variants = array();

                    $result1[0]['precio_venta'] = get_cost($result[0]['precio_venta']);
                    $result1[0]['precio_venta'] = number_format($result1[0]['precio_venta'], 2);

                    foreach ($matches[1] as $key => $value) {
                        $titulo = $matches[1][$key];
                        $valor = $matches[2][$key];


                        if (!isset($all_variants[$titulo])) {
                            $all_variants[$titulo] = array();
                        }

                        $all_variants[$titulo][] = $valor;
                        $all_variants[$titulo] = array_unique($all_variants[$titulo]);

                        
                    }

                    preg_match_all($pattern, $result[0]['variante'], $matches_this, PREG_PATTERN_ORDER);

                    $this_variant = array();

                    foreach ($matches_this[1] as $key => $value) {
                        $titulo = $matches_this[1][$key];
                        $valor = $matches_this[2][$key];


                        if (!isset($this_variant[$titulo])) {
                            $this_variant[$titulo] = $valor;
                        }

                    }

                    $result1[0]['atributos'] = $all_variants;
                    $response = array(
                        'status' => 'success',
                        'variant' => $this_variant,
                        'producto' => $result1[0],
                        'Favorito' => "Si",
                    );
                    die(json_encode($response));
                } else {
                    $response = array(
                        'status' => 'error 1',
                        'message' => 'No tiene stock'
                    );
                    die(json_encode($response));
                }
            }
        }
    } else {
        foreach ($result1 as $fila) {
            if ($fila['stock'] === "Si") {

                $pattern = '/(\p{L}+):\s*(.*?)\s*(?=(\p{L}+):|$)/u';

                preg_match_all($pattern, $fila['variante'], $matches, PREG_PATTERN_ORDER);

                $all_variants = array();

                $result1[0]['precio_venta'] = get_cost($result[0]['precio_venta']);
                $result1[0]['precio_venta'] = number_format($result1[0]['precio_venta'], 2);

                foreach ($matches[1] as $key => $value) {
                    $titulo = $matches[1][$key];
                    $valor = $matches[2][$key];


                    if (!isset($all_variants[$titulo])) {
                        $all_variants[$titulo] = array();
                    }

                    $all_variants[$titulo][] = $valor;
                    $all_variants[$titulo] = array_unique($all_variants[$titulo]);

                    
                }

                preg_match_all($pattern, $result[0]['variante'], $matches_this, PREG_PATTERN_ORDER);

                $this_variant = array();

                foreach ($matches_this[1] as $key => $value) {
                    $titulo = $matches_this[1][$key];
                    $valor = $matches_this[2][$key];


                    if (!isset($this_variant[$titulo])) {
                        $this_variant[$titulo] = $valor;
                    }

                }

                $result1[0]['atributos'] = $all_variants;
                $response = array(
                    'status' => 'success',
                    'variant' => $this_variant,
                    'producto' => $result1[0],
                    'producto2' => $result[0],

                );
                die(json_encode($response));
            } else {
                $response = array(
                    'status' => 'error 1',
                    'message' => 'No tiene stock'
                );
                die(json_encode($response));
            }
        }
    }
} else {
    $response = array(
        'status' => 'error 2',
        'message' => 'Producto no cargado'
    );
    die(json_encode($response));
}
