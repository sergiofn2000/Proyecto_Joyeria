<?php
session_start();

// $response = array(
//     'status' => 'denied',
//     'message' => 'se mete aqui',
//     'datos' => $_POST,


// );
// die (json_encode($response));

require_once('./handlers/mail_handler.php');


$nombre_usuario = $_POST["nombre_usuario"];
$apellido_usuario = $_POST["apellido_usuario"];
$telefono_usuario = $_POST["telefono_usuario"];
$correo_usuario = $_POST["correo_usuario"];
$mensaje_usuario = $_POST["mensaje_usuario"];
$asunto = $_POST["asunto"];
$correo_personal = "contacto@prueba.com";





$send_mail = new mail_handler();
$send_mail->to = $correo_personal; // ok
$send_mail->subject = $asunto; //ok
$send_mail->body = "De: $nombre_usuario $apellido_usuario <br> Correo: $correo_usuario <br> Numero de telf: $telefono_usuario <br> El mensaje es:<br><br>  $mensaje_usuario";

$result = $send_mail->send();
echo empty($result);

if (!$result) {
    $response = array(
        'status' => 'denied',
        'message' => 'Se ha producido un error. Inténtelo de neuvo más tarde.',
    );
    die(json_encode($response));
}

$response = array(
    'status' => 'success',
);
die(json_encode($response));