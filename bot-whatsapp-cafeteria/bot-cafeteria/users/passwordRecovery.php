<?php
include_once ('confi.php');
include_once ('DB/adminDB.php');

class PasswordRecovery{
    static public function initPasswordRecovery($requests){
        $mail = $requests['AD_email_'];
        $password = PasswordRecovery::generatePassword();  
        $passwordEncripted = PasswordRecovery::encryptPassword($password);      
        $update = AdminDb::updatePassword($mail, $passwordEncripted);
        if($update == true){
            PasswordRecovery::sendMail($mail, $password);
            echo json_encode(['status' => '200', 'message' => 'Password actualizado correctamente']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Error al actualizar password']);
        }
    }

    //enviar un mail con una contraseña nueva
    static public function sendMail($mail, $password){
        $destinatario = $mail;
        $asunto = "shopbot.pro - Cambio de Password";
        $cuerpo = " 
            <html> 
            <head> 
               <meta charset='UTF-8' />
               <title>shopbot.pro - Cambio de Password</title> 
            </head> 
            <body> 
            <h1>shopbot.pro</h1> 

            <h3>Aquí tienes tu nuevos datos de acceso:</h3>.
            <ul>
                <li>Usuario: $mail</li>
                <li>Contraseña: $password</li>
            </ul>
            <p> Puedes personalizarlo cuando quieras en tu panel principal.</p> 

            </body> 
            </html> 
        ";
        //para el envío en formato HTML 
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        //dirección del remitente 
        $headers .= "From: shopbot.pro - Nuevo Password <".MAIL_PASSWORD_RECOVERY.">\r\n";

        mail($destinatario, $asunto, $cuerpo, $headers);
    }

    //generar un password aleatorio con letras mayusculas, minusculas y numeros
    static public function generatePassword(){
        $caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = "";
        for ($i = 0; $i < 8; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $password;
    }

    //encriptar password segun el algorithmo de sha256
    static public function encryptPassword($password){
        $password = hash('sha256', $password);
        return $password;
    }
}
