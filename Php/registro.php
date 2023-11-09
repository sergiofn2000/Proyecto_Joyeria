<?php
session_start();
require_once('./handlers/db_handler.php');
require_once('./handlers/mail_handler.php');
require_once('./handlers/token_handler.php');

$tokenhandler = new token_handler();

// Envio de correo
function send_verify_mail($mail, $user, $id) {
    $tokenhandler = new token_handler();
    $token = $tokenhandler -> create_token(array('userId' => $id), 'activateAccount', $id, '1 hours', false);

    $verify_url = 'https://joyeria.local.com/activateaccount/' . $token;

    $verify_mail = new mail_handler();
    $verify_mail -> to = $mail;
    $verify_mail -> subject = "Bienvenido a Joyeria";
    $verify_mail -> add_embedded_image('./../Fotos/Logo/Logo.png', 'logo');
    $verify_mail -> body = '   
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:arial, "helvetica neue", helvetica, sans-serif"><head><meta charset="UTF-8"><meta content="width=device-width, initial-scale=1" name="viewport"><meta name="x-apple-disable-message-reformatting"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta content="telephone=no" name="format-detection"><title>New message</title><!--[if (mso 16)]><style type="text/css"> a {text-decoration: none;} </style><![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]><xml> <o:OfficeDocumentSettings> <o:AllowPNG></o:AllowPNG> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><![endif]--><!--[if !mso]><!-- --><link href="https://fonts.googleapis.com/css2?family=Imprima&display=swap" rel="stylesheet"><!--<![endif]--><style type="text/css">#outlook a { padding:0;}.es-button { mso-style-priority:100!important; text-decoration:none!important;}a[x-apple-data-detectors] { color:inherit!important; text-decoration:none!important; font-size:inherit!important; font-family:inherit!important; font-weight:inherit!important; line-height:inherit!important;}.es-desk-hidden { display:none; float:left; overflow:hidden; width:0; max-height:0; line-height:0; mso-hide:all;}@media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1, h2, h3, h1 a, h2 a, h3 a { line-height:120% } h1 { font-size:30px!important; text-align:left } h2 { font-size:24px!important; text-align:left } h3 { font-size:20px!important; text-align:left } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:30px!important; text-align:left } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:24px!important; text-align:left } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important; text-align:left } .es-menu td a { font-size:14px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class="gmail-fix"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button, button.es-button { font-size:18px!important; display:block!important; border-right-width:0px!important; border-left-width:0px!important; border-top-width:15px!important; border-bottom-width:15px!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; max-height:inherit!important } }</style></head>
        <body style="width:100%;font-family:arial, "helvetica neue", helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0"><div class="es-wrapper-color" style="background-color:#FFFFFF"><!--[if gte mso 9]><v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t"> <v:fill type="tile" color="#ffffff"></v:fill> </v:background><![endif]--><table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#FFFFFF"><tr><td valign="top" style="padding:0;Margin:0"><table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top"><tr><td align="center" style="padding:0;Margin:0"><table bgcolor="#bcb8b1" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"><tr><td align="left" style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:40px;padding-right:40px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:520px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" style="padding:0;Margin:0;display:none"></td>
        </tr></table></td></tr></table></td></tr></table></td>
        </tr></table><table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"><tr><td align="center" style="padding:0;Margin:0"><table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#EFEFEF;border-radius:20px 20px 0 0;width:600px"><tr><td align="left" style="padding:0;Margin:0;padding-top:40px;padding-left:40px;padding-right:40px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:520px"><table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" class="es-m-txt-c" style="padding:0;Margin:0;font-size:0px"><img src="cid:logo" alt="" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;border-radius:100px;font-size:12px;max-width: 90%;"  title="Confirm email"></td>
        </tr></table></td></tr></table></td></tr><tr><td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:40px;padding-right:40px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:520px"><table cellpadding="0" cellspacing="0" width="100%" bgcolor="#fafafa" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#fafafa;border-radius:10px" role="presentation"><tr><td align="left" style="padding:20px;Margin:0"><h3 style="Margin:0;line-height:34px;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;font-size:28px;font-style:normal;font-weight:bold;color:#2D3142">Bienvenido, ' . $user . '</h3>
        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:27px;color:#2D3142;font-size:18px"><br></p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:27px;color:#2D3142;font-size:18px">Estás recibiendo este mensaje ya que recientemente has creado una cuenta en nuestra página web.</p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:27px;color:#2D3142;font-size:18px"><br>Por razones de seguridad, la cuenta debe ser activada tras su creación. Para activarla, haga clic en el siguiente enlace.</p></td></tr></table></td></tr></table></td></tr></table></td>
        </tr></table><table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"><tr><td align="center" style="padding:0;Margin:0"><table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#EFEFEF;width:600px"><tr><td align="left" style="Margin:0;padding-top:30px;padding-bottom:40px;padding-left:40px;padding-right:40px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:520px"><table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" style="padding:0;Margin:0"><!--[if mso]><a href="' . $verify_url . '" target="_blank" hidden> <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" esdevVmlButton href="' . $verify_url . '" style="height:56px; v-text-anchor:middle; width:520px" arcsize="50%" stroke="f" fillcolor="#f4e0aa"> <w:anchorlock></w:anchorlock> <center style="color:#000000; font-family:Imprima, Arial, sans-serif; font-size:22px; font-weight:700; line-height:22px; mso-text-raise:1px">Activar cuenta</center> </v:roundrect></a><![endif]--><!--[if !mso]><!-- --><span class="msohide es-button-border" style="border-style:solid;border-color:#2CB543;background:#f4e0aa;border-width:0px;display:block;border-radius:30px;width:auto;mso-border-alt:10px;mso-hide:all"><a href="' . $verify_url . '" class="es-button msohide" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#000000;font-size:22px;padding:15px 20px 15px 20px;display:block;background:#f4e0aa;border-radius:30px;font-family:Imprima, Arial, sans-serif;font-weight:bold;font-style:normal;line-height:26px;width:auto;text-align:center;mso-hide:all;padding-left:5px;padding-right:5px;border-color:#f4e0aa">Activar cuenta</a></span><!--<![endif]--></td>
        </tr></table></td></tr></table></td></tr><tr><td align="left" style="padding:0;Margin:0;padding-left:40px;padding-right:40px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:520px"><table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="left" style="padding:0;Margin:0"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:27px;color:#2D3142;font-size:18px">Atentamente,</p>
        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:27px;color:#2D3142;font-size:18px"><br>Joyeria</p></td></tr><tr><td align="center" style="padding:0;Margin:0;padding-bottom:20px;padding-top:40px;font-size:0"><table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td style="padding:0;Margin:0;border-bottom:1px solid #666666;background:unset;height:1px;width:100%;margin:0px"></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td>
        </tr></table><table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"><tr><td align="center" style="padding:0;Margin:0"><table bgcolor="#efefef" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#EFEFEF;border-radius:0 0 20px 20px;width:600px"><tr><td class="esdev-adapt-off" align="left" style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:40px;padding-right:40px"><table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:520px"><tr><td class="esdev-mso-td" valign="top" style="padding:0;Margin:0"><table cellpadding="0" cellspacing="0" align="left" class="es-left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:47px"><table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" class="es-m-txt-l" style="padding:0;Margin:0;font-size:0px"><img src="https://djsexw.stripocdn.email/content/guids/CABINET_ee77850a5a9f3068d9355050e69c76d26d58c3ea2927fa145f0d7a894e624758/images/group_4076325.png" alt="Demo" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;font-size:12px" width="47" title="Demo"></td>
        </tr></table></td></tr></table></td><td style="padding:0;Margin:0;width:20px"></td><td class="esdev-mso-td" valign="top" style="padding:0;Margin:0"><table cellpadding="0" cellspacing="0" class="es-right" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right"><tr><td align="center" valign="top" style="padding:0;Margin:0;width:453px"><table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="left" style="padding:0;Margin:0"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:24px;color:#2D3142;font-size:16px">Este enlace expira en 1 hora.</p></td></tr></table></td></tr></table></td></tr></table></td></tr></table></td>
        </tr></table><table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top"><tr><td align="center" style="padding:0;Margin:0"><table bgcolor="#bcb8b1" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"><tr><td align="left" style="Margin:0;padding-left:20px;padding-right:20px;padding-bottom:30px;padding-top:40px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="left" style="padding:0;Margin:0;width:560px"><table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" style="padding:0;Margin:0;padding-top:20px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Imprima, Arial, sans-serif;line-height:21px;color:#2D3142;font-size:14px"><a target="_blank" href="" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2D3142;font-size:14px"></a>Copyright © 2023&nbsp;Sergio Franco Navas<a target="_blank" href="" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2D3142;font-size:14px"></a></p>
        </td></tr></table></td></tr></table></td></tr></table></td>
        </tr></table><table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top"><tr><td align="center" style="padding:0;Margin:0"><table bgcolor="#ffffff" class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px"><tr><td align="left" style="padding:20px;Margin:0"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="left" style="padding:0;Margin:0;width:560px"><table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"><tr><td align="center" style="padding:0;Margin:0;display:none"></td>
        </tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr></table></div></body></html>
    ';
    $result = $verify_mail -> send();
    return $result;
}

// Registro
if (isset($_REQUEST) && isset($_REQUEST['type']) && $_REQUEST['type'] == 'singup') {
    // Obtiene la información y la almacena en variables
    $user = $_POST['data']['user'];
    $mail = $_POST['data']['mail'];
    $name = $_POST['data']['name'];
    $date_of_birth = $_POST['data']['date_of_birth'];
    $pass = $_POST['data']['pass'];
    $confirm_pass = $_POST['data']['confirm_pass'];
    $check = $_POST['data']['check'] == 'true';

    // Comprueba si el nombre tiene entre 3 y 16 carácteres alfanuméricos.
    if (!(ctype_alnum($user) && (strlen($user) >=3 && strlen($user) <=16))) {
        $response = array (
            'status' => 'denied',
            'message' => 'El nombre de usuario debe contener entre 3 y 16 carácteres. Únicamente se permiten letras y números.'
        );
        die(json_encode($response));
    }

    // Verifica el nombre completo

    if (!preg_match('/^(?:[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]{1,}(?:\s|$)){1,5}$/u', $name)) {
        $response = array(
            'status' => 'denied',
            'message' => 'El nombre completo que ha introducido no tiene un formato válido.'
        );
        die (json_encode($response));
    }

    if (!((strlen($name) >=3 && strlen($name) <=255))) {
        $response = array (
            'status' => 'denied',
            'message' => 'El nombre completo debe contener entre 3 y 255 carácteres.'
        );
        die(json_encode($response));
    }

    // Verificar fecha de nacimiento
    $today = new DateTime();
    $birth = DateTime::createFromFormat('Y-m-d', $date_of_birth);
    $age = $today->diff($birth)->y;

    if (!preg_match('/^(19[0-9]{2}|20[0-2][0-9]|2030)-((0?[1-9])|1[0-2])-((0?[1-9])|([12][0-9])|3[01])$/', $date_of_birth)) {
        $response = array(
            'status' => 'denied',
            'message' => 'Introduzca uan fecha de nacimiento válida.'
        );
        die (json_encode($response));
    }

    if ($age < 18) {
        $response = array(
            'status' => 'denied',
            'message' => 'Debe ser mayor de edad para poder registrarse.'
        );
        die (json_encode($response));
    }
    

    // Verficiar longitud de contraseña
    if (!(strlen($pass) >=8 && strlen($pass) <=16)){
        $response = array(
            'status' => 'denied',
            'message' => 'La contraseña debe contener entre 8 y 16 carácteres.'
        );
        die (json_encode($response));
    }

    // Verificar carácteres de contraseña
    $contador1 = 0;
    $contador2 = 0;
    $contador3 = 0;
    $contador4 = 0;

    for($i = 0; $i <strlen($pass); $i++){

        if(ctype_upper($pass[$i])){
            $contador1++;
        }elseif(ctype_lower($pass[$i])){
            $contador2++;
        }elseif(ctype_digit($pass[$i])){
            $contador3++;
        }elseif(ctype_punct($pass[$i])){
            $contador4++;
        }
    }
    if(!($contador1 > 0 && $contador2 > 0 && $contador3 > 0 && $contador4 > 0)){
        $response = array(
            'status' => 'denied',
            'message' => 'La contraseña debe contener al menos: <ul><li>Una letra minúscula.</li><li>Una letra mayúscula.</li><li>Un número.</li><li>Un símbolo.</li></ul>'
        );
        die (json_encode($response));
    }

    // Verificar si las contraseñas coinciden
    if(!($pass === $confirm_pass)){
        $response = array(
            'status' => 'denied',
            'message' => 'Las contraseñas no coinciden.'
        );
        die (json_encode($response));
    }

    // Verificar si el correo es válido
    if (!preg_match('/^[\w.-_]+@([a-zA-Z_]+\.)+[a-zA-Z]{2,3}$/', $mail)) {
        $response = array(
            'status' => 'denied',
            'message' => 'Debe introducir una dirección de correo electrónico válida.'
        );
        die (json_encode($response));
    }

    // Verificar casilla de aceptación
    if(!$check){
        $response = array(
            'status' => 'denied',
            'message' => 'Debe aceptar la política de privacidad.'
        );
        die (json_encode($response));
    }

    // Iniciar conexión con la base de datos
    $conn = new db_handler();

    // Comprobar si el usuario ya existe
    $result = $conn -> query("SELECT * FROM registros_user WHERE nombre_usuario = ?", 's', [$user]);
    if (!empty($result[0])) {
        $response = array(
            'status' => 'denied',
            'message' => 'El nombre de usuario introducido ya está en uso.'
        );
        die (json_encode($response));
    }

    // Comprobar si el correo ya existe
    $result = $conn -> query("SELECT * FROM registros_user WHERE correo = ?", 's', [$mail]);
    if (!empty($result[0])) {
        $response = array(
            'status' => 'denied',
            'message' => 'La dirección de correo electrónico introducida ya está en uso.'
        );
        die (json_encode($response));
    }

    // Registrar usuario
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    $conn -> execute("INSERT INTO registros_user (nombre_usuario, contraseña, correo, nombre_completo, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)", 'sssss', [$user, $hashed_pass, $mail, $name, $date_of_birth]);

    // Enviar correo de verificación
    
    $result = $conn -> query("SELECT id FROM registros_user WHERE nombre_usuario = ?", 's', [$user]);
    $id = $result[0]['id'];

    if (!send_verify_mail($mail, $user, $id)) {
        $conn -> execute("DELETE FROM registros_user WHERE id = ?", 'i', [$id]);
        $response = array(
            'status' => 'denied',
            'message' => 'La dirección de correo electrónico introducida no existe.'
        );
        die (json_encode($response));
    }

    // Confirmar registro
    $response = array('status' => 'success');
    die(json_encode($response));

// Verificar cuenta
} else if (isset($_REQUEST) && isset($_REQUEST['type']) && $_REQUEST['type'] == 'activateAccount') {

    // Obtiene la información y la almacena en variables
    $token = $_POST['data']['token'];

    // Establece conexión con la base de datos
    $conn = new db_handler();

    // Comprueba si el token es válido, y almacena el nombre de usuario en una variable
    
    $tokenresult = $tokenhandler -> verify_token($token, 'activateAccount');
    

    if (empty($tokenresult)) {
        $response = array(
            'status' => 'denied',
            'message' => 'El enlace ha espirado. Introduzca sus credenciales para obtener un nuevo enlace de verificación.'
        );
        die(json_encode($response));
    }
    $data = $tokenresult['data'];
    $id = $data['userId'];
    $tokenhandler -> cancel_user_tokens($id, 'activateAccount');

    // Activa la cuenta del usuario
    $conn -> execute("UPDATE registros_user SET verificado = 1 WHERE id = ?", 's', [$id]);

    // Respuesta
    $response = array(
        'status' => 'success'
    );
    die(json_encode($response));
}

