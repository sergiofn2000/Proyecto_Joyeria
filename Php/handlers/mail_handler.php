<?php
    require_once('./libraries/PHPMailer/src/Exception.php');
    require_once('./libraries/PHPMailer/src/PHPMailer.php');
    require_once('./libraries/PHPMailer/src/SMTP.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class mail_handler {
        private $mail = '';
        
        public $to = '';
        public $subject = '';
        public $body = '';

        function __construct () {
            $env = parse_ini_file('./../.env');

            $mail = new PHPMailer();
            $mail -> isSMTP();
            $mail -> CharSet = 'UTF-8';
            $mail -> Host = $env['MAIL_HOST'];
            $mail -> SMTPDebug = 0;
            $mail -> SMTPAuth = true;
            $mail -> SMTPSecure = 'tsl';
            $mail -> Port = $env['MAIL_PORT'];
            $mail -> Username = $env['MAIL_USER'];
            $mail -> Password = $env['MAIL_PASS'];
            $mail -> isHTML();
            $mail -> setFrom($env['MAIL_USER'], "Joyeria");
            $this -> mail = $mail;
        }

        function add_embedded_image($img, $name) {
            $this -> mail -> AddEmbeddedImage($img, $name);
        }

        function send() {
            try {
                $mail = $this -> mail;
                $mail -> clearAddresses();
                $mail -> addAddress($this -> to);
                $mail -> Subject = $this -> subject;
                $mail -> Body = $this -> body;
                return $mail -> Send();
            } catch (Exception $e) {
                return $e -> getMessage();
            }
        }
    }
?>