<?php

$products = array();
require_once('./handlers/price_handler.php');
require_once('./handlers/db_handler.php');



// Funcion para añadir las categorias

$categories = array();
function add_category($category, &$categories) {
    $sub_categories = explode(" / ", $category);

    $current_category = &$categories;
    
    foreach ($sub_categories as $x) {
        if (isset($current_category[$x])) {
            $current_category = &$current_category[$x];
        } else {
            if (str_ends_with($x, '-')) {
                $current_category[$x] = $category;
            } else {
                $current_category[$x] = array();
                $current_category = &$current_category[$x];
            }
        }
    }

}

// SELECT id, ref_producto, descripcion, precio_venta, imagen_ftp
//     FROM productos
//     GROUP BY ref_producto
//     ORDER BY CASE
//         WHEN categorias = 'Joyas Oro de 18 kl / Alianzas / Oro Blanco -' THEN 0
//         WHEN categorias LIKE 'Joyas Oro de 18 kl / %' THEN 1
//         ELSE 2
//     END, descripcion;





// Obtener productos
if(isset($_POST) && isset($_POST['type']) && $_POST['type'] == 'getProducts') {
    $conex = new db_handler();
    $result = $conex->query2("SELECT id, ref_producto, descripcion, precio_venta, imagen_ftp
    FROM productos
    GROUP BY ref_variante
    ORDER BY CASE
        WHEN categorias = 'Joyas Oro de 18 kl / Alianzas / Oro Blanco -' THEN 0
        WHEN categorias LIKE 'Joyas Oro de 18 kl / %' THEN 1
        ELSE 2
    END, CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(ref_variante, 'n', -1), '_', 1) AS UNSIGNED) ASC;");
    if (!empty($result)) {
        foreach ($result as $fila) {
            $fila['precio_venta'] = get_cost($fila['precio_venta']);
            $fila['precio_venta'] = number_format($fila['precio_venta'], 2);
            array_push($products, $fila);
        }
        
        $response = array(
            'status' => 'success',
            'message' => 'Productos cargados',
            'productos' => $products,

        );
        die(json_encode($response));
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Productos no cargados',

        );
        die(json_encode($response));
    }


}

// Obtener productos por categoria
if(isset($_POST) && isset($_POST['type']) && $_POST['type'] == 'getProductsByCategory') {
    $category = $_POST['data']['category'];
    $conex = new db_handler();
    $result = $conex->query("SELECT id,ref_producto,descripcion,precio_venta,imagen_ftp
                                     FROM productos
                                     WHERE categorias = ?
                                     GROUP BY ref_producto
                                     ", 's', [$category]);
                                    
                                    //  FROM productos
                                    //  WHERE categorias = ?
                                    //  GROUP BY ref_producto
                                    //  ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(ref_variante, 'n', -1), '_', 1) AS UNSIGNED) DESC;
    if (!empty($result)) {
        foreach ($result as $fila) {
            $fila['precio_venta'] = get_cost($fila['precio_venta']);
            $fila['precio_venta'] = number_format($fila['precio_venta'], 2);
            array_push($products, $fila);
        }
        $response = array(
            'status' => 'success',
            'message' => 'Productos cargados',
            'productos' => $products,

        );
        die(json_encode($response));
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Productos no cargados',

        );
        die(json_encode($response));
    }


} 

// Obtener categorias
if (isset($_POST) && isset($_POST['type']) && $_POST['type'] == 'getCategories') {
    
    // Realiza consulta a la base de datos
    $conn = new db_handler();
    $result = $conn -> query2("SELECT DISTINCT categorias FROM `productos`  ORDER BY categorias");

    // Añadir todas las categorias
    foreach ($result as $y) {
        $cat = &$categories;
        add_category($y['categorias'], $cat);
    }

    // Respuesta
    $response = array(
        'status' => 'success',
        'categories' => $categories
    );
    die(json_encode($response));
}


// $conex = new db_handler();
// $result = $conex->query2("select nombre,categoria,precio
//                                     from productos");
// if (!empty($result)) {
//     foreach ($result as $fila) {
//         array_push($array, $fila);
//     }
//     $response = array(
//         'status' => 'success',
//         'message' => 'Productos cargados',
//         'productos' => $array,

//     );
//     die(json_encode($response));
// } else {
//     $response = array(
//         'status' => 'error',
//         'message' => 'Productos no cargados',

//     );
//     die(json_encode($response));
// }

?>