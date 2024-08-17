<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer/PHPMailer.php';
require 'PHPMailer/PHPMailer/SMTP.php';
require_once 'ReCaptcha/autoload.php';

// Verifica el token de reCAPTCHA v3
$recaptcha_secret = '6LfdHOAnAAAAABnmxA2fdrJXEd-Gm-DjeAR46Cn_';
$recaptcha_response = $_POST['recaptcha_token'];
$recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret);
$response = $recaptcha->verify($recaptcha_response, $_SERVER['REMOTE_ADDR']);

//Variable para almacenar array con informacion de Recaptcha Response
$printScore = '<pre>' . var_export($response, true) . '</pre>';

if (!$response->isSuccess()) {
    echo "<script> alert('Error: El token de reCAPTCHA v3 es inválido, intentalo nuevamente.');
         window.location.href = '/';
         </script>";
} else {
    //Si el score es menor a 0,7, se detecta como spam
    $score = $response->getScore();
    if ($score < 0.7) {
        echo "<script> alert('Error: El puntaje de reCAPTCHA v3 es demasiado bajo, intentalo nuevamente.');
             window.location.href = '/';
             </script>";
    } else {
        // Procesa el formulario

        //Declarando los fields para incluirlos en el Body
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $url = $_SERVER['HTTP_REFERER'];
        $body = "
            <head>  
              <style> 
                body { 
                    height: 100%; width: 100%; max-width: 100%;
                    font-family: 'Montserrat', sans-serif; 
                    font-weight: 400;
                    background-color: #FAFAFA;
                    overflow: hidden;
                    padding: 20px;
                }   
                p { font-size: 14px; font-family: 'Montserrat', sans-serif; font-weight: 400; color: #233645; }
            
                .blue { color: #233645; font-family: 'Montserrat', sans-serif; font-weight: 400; }
                .bold { font-weight: bold; }
                .title { font-size: 36px; }
              </style>
            </head>
            
            <body>
                <div>
                <div>
                    <h1 class='blue title'><b>Formulario de Contacto VISA EB-5</b></h1>
                
            
                    <p>Nombre: <span class='bold'> $nombre</span></p>
                    <p>Email: <span class='bold'> $email</span></p>
                    <p>Telefono: <span class='bold'> $telefono</span></p>
                    <p>URL: <span class='bold'> $url</span></p>
                    
                </div>
                </div>  
                <footer>
                    <h4 class=bold></h4>
                    <span class=grey>Este formulario a sido completado Desde $url</span><br/><br/>
                    <img src='https://mohawk.rileagroup.com/assets/images/mohawk-logo-black.png' style='width: 200px' />
                </footer>

            </body>
            ";

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = 'smtp.office365.com'; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = 'mohawk@rileagroup.com'; //SMTP username
            $mail->Password = "mAgxu?/;-I'*8`$6CT.K"; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
            $mail->Port = 587; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->addReplyTo($email);
            $mail->setFrom('mohawk@rileagroup.com', 'Mohawk No Reply');
            $mail->addAddress('julianbattaglino@gmail.com', 'Julian Battaglino (Webmaster)'); //Add a recipient
            $mail->addAddress('camilalongosiage@gmail.com', 'Camila Longo'); //Add a recipient
            $mail->addAddress('pierredesaintleger@gmail.com', 'Pierre De Saint Leger'); //Add a recipient

            

            //$mail->addAddress('ellen@example.com');                                   //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('info@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments

            // Agrega el archivo adjunto al correo
            //$mail->addAttachment($cv_destination, $cv_name);

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'Mohawk Formulario VISA EB-5';
            $mail->Body = $body;
            $mail->AltBody = $mensaje;

            $mail->send();

            /// Alerta Javascript luego del envio exitoso, y redirecci車n al index.html
            echo "
    <script> alert('Gracias por contactarte con nosotros. responderemos lo antes posible.');
    window.location.href = '/';
    </script>";
        } catch (Exception $e) {
            echo "
    <script> alert('Hubo un error, no se pudo enviar el mensaje, intentalo nuevamente.');
    window.location.href = '/';
    </script>";
        }
    }
}