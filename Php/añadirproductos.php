<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio Formulario 2</title>
</head>

<body>

    <h1>Pedido</h1><br>

    <?php
    session_start();
    $host = 'localhost';
    $usuario = 'root';
    $contraseña = '';
    $bd = 'oropinto';
    $conexion = mysqli_connect($host, $usuario, $contraseña, $bd)
        or die('Problemas con la conexión');
    $datos = mysqli_query($conexion, "select distinct categoria from productos")
        or die('Problemas al seleccionar: ' . mysqli_error($conexion));

    echo "<form method='post'>";
    while ($fila = mysqli_fetch_array($datos)) {
        echo "<h2>$fila[categoria]</h2>";
        $datos2 = mysqli_query($conexion, "select id,nombre,precio from productos
                                       where categoria='$fila[categoria]'")
            or die('Problemas al seleccionar: ' . mysqli_error($conexion));
        while ($fila = mysqli_fetch_array($datos2)) {

            echo "<br> $fila[nombre]<input type='number' min='0' max='15' name='$fila[id]'>";
            echo " Precio<input type='number' min='0' max='15' readonly name='$fila[precio]' value='$fila[precio]'><br>";
        }
    }
    echo "<br><br><input type='submit' name='producto' value='Añadir al Carrito'>
     <input type='submit' name='carrito' value='Ir al Carrito'>  <input type='submit' name='Volver' value='Volver al inicio'></form>";

    if (isset($_REQUEST['producto'])) {
        unset($_REQUEST['producto']);
        unset($_REQUEST['carrito']);
        unset($_REQUEST['volver']);
        $clv = array_keys($_REQUEST);
        $val = array_values($_REQUEST);
        // Filtrar campos que sean int y estén vacíos
        $filtered = array_filter($val, function ($item) {
            return ($item !== '' && ctype_digit(strval($item)));
        });
        $filteredKeys = array_keys($filtered);
        $filteredValues = array_values($filtered);
        $filteredCount = count($filteredValues);
        // Verificar si se eliminan campos de int vacíos
        for ($i = 0; $i < count($val); $i++) {
            if ($i >= $filteredCount && ctype_digit(strval($val[$i])) && intval($val[$i]) === 0) {
                $nextIndex = $i + 1;
                if ($nextIndex < count($val) && is_string($val[$nextIndex])) {
                    array_splice($clv, $i, 1);
                    array_splice($val, $i, 1);
                }
            }
        }

        // Agregar al carrito los campos no vacíos
        for ($i = 0; $i < count($filteredValues); $i++) {
            $index = $filteredKeys[$i];
            $cantidad = $filteredValues[$i];
            $datos3 = mysqli_query($conexion, "select id_producto
                                                from carrito
                                                where id_usuario='$_SESSION[id]' AND id_producto='$clv[$index]'")
                or die('Problemas al seleccionar: ' . mysqli_error($conexion));

            if ($fila = mysqli_fetch_array($datos3)) {
                echo "hola";
                mysqli_query($conexion, "UPDATE carrito SET cantidad=cantidad+$cantidad, precio=precio+($cantidad*(SELECT precio FROM productos WHERE id='$clv[$index]')) WHERE id_producto='$clv[$index]'")
                    or die('Problemas al seleccionar: ' . mysqli_error($conexion));
            } else {
                mysqli_query($conexion, "INSERT INTO carrito(id_usuario, id_producto, cantidad, precio) 
                VALUES ('$_SESSION[id]', '$clv[$index]', '$cantidad', ($cantidad*(SELECT precio FROM productos WHERE id='$clv[$index]')))");
            }
        }
    }
    if (isset($_REQUEST['carrito'])) {
        header("location:carrito.php");
    }
    if (isset($_REQUEST['Volver'])) {
        header("location:../index.html");
    }

    ?>