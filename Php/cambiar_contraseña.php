<?php
require_once('./handlers/db_handler.php');
require_once('./handlers/token_handler.php');

$tokenhandler = new token_handler();

session_start();
if (isset($_POST['type']) && $_POST['type'] == 'changepass') {

    $nombre_usuario = $_SESSION['nombre'];
    $id = $_SESSION['id'];
    $contra_actual = $_POST["contra_actual"];
    $contra_nueva_1 = $_POST["contra_nueva_1"];
    $contra_nueva_2 = $_POST["contra_nueva_2"];

    $contador1 = 0; //verificar caracteres
    $contador2 = 0;
    $contador3 = 0;
    $contador4 = 0;
    $contfinal = 0;
    
    $conn = new db_handler();
    $peticion = $conn->query("select * from registros_user WHERE id=? ", "i", [$id]);

    if (empty($peticion[0])) {
        $response = array(
            'status' => 'error',
            'message' => 'Se ha producido un error. Intentelo de nuevo más tarde.',
        );
        die(json_encode($response));
    }


    $contraseña_cifrada = $peticion[0]['contraseña'];

    $password_valid = password_verify($contra_actual, $contraseña_cifrada);

    if ($password_valid){

        if ($contra_nueva_1 == $contra_nueva_2) {

            for ($i = 0; $i < strlen($contra_nueva_2); $i++) {   //recorro la string     

                if (ctype_upper($contra_nueva_2[$i])) { //compruebo cada caracter si es mayúscula
                    $contador1++;
                } elseif (ctype_lower($contra_nueva_2[$i])) { //compruebo cada caracter si es minúscula
                    $contador2++;
                } elseif (ctype_digit($contra_nueva_2[$i])) { //compruebo cada caracter si es un número
                    $contador3++;
                } elseif (ctype_punct($contra_nueva_2[$i])) { //compruebo cada caracter si es un símbolo no alfanumérico ni espacio
                    $contador4++;
                }
            }

            if (!strlen($contra_nueva_2) >= 8 && strlen($contra_nueva_2) <= 16) { //longitud permitida
               
                $response = array(
                    'status' => 'error',
                    'message' => 'La nueva contraseña debe contener entre 8 y 16 caracteres.',

                );
                die(json_encode($response));
            }
            if (!(($contador1 > 0 && $contador2 > 0 && $contador3 > 0 && $contador4 > 0))) {
                $response = array(
                    'status' => 'error',
                    'message' => 'La contraseña debe contener al menos: <ul><li>Una letra minúscula.</li><li>Una letra mayúscula.</li><li>Un número.</li><li>Un símbolo.</li></ul>',

                );
                die(json_encode($response));
            } else {
                $contfinal++;
            }

            if ($contfinal >= 1) {
               
                $hashed_pass = password_hash($contra_nueva_2, PASSWORD_DEFAULT);

                $peticion2 = $conn -> execute("UPDATE registros_user SET Contraseña=? WHERE nombre_usuario=?",'ss', [$hashed_pass, $nombre_usuario]);

                $tokenhandler -> cancel_user_tokens($id, 'loginToken');

                    $response = array(
                        'status' => 'success',
                        'message' => 'La contraseña ha sido actualizada con éxito.',
                    );
                    die(json_encode($response));
            }
            
        }else {

            $response = array(
                'status' => 'denied',
                'message' => 'Las contraseñas no coinciden.',
                
            );
            die(json_encode($response));}

    }else {

        $response = array(
            'status' => 'denied',
            'message' => 'La contraseña actual es incorrecta.',
        );
        die(json_encode($response));
    }
}else {

    $response = array(
        'status' => 'error',
        'message' => 'Se ha producido un error, intentelo de nuevo más tarde.',
    );
    die(json_encode($response));
}
