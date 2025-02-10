<?php
include_once ('DB/adminDB.php');
include_once ('confi.php');


class Disconnected {
    static public function initDisconnected($requests){
        $adminPhone = $requests['adminPhone'];
        $mail = AdminDb::getMail($adminPhone);

        Disconnected::sendMail($mail, $adminPhone);
    }

    //enviar un mail con una contraseña nueva
    static public function sendMail($mail, $adminPhone)
    {
        $destinatario = $mail;
        $asunto = "SELBOTPRO - BOT DESCONECTADO";
        $cuerpo = " 
            <html> 
            <head> 
               <meta charset='UTF-8' />
               <title>SELBOTPRO - bot desconectado</title> 
            </head> 
            <body> 
            <h1>SELBOTPRO</h1> 
            <h3>TU BOT ESTÁ DESCONECTADO</h3>.
            <p> Puedes reiniciarlo en tu panel principal.</p>
            
            </body> 
            </html> 
        ";
        //para el envío en formato HTML 
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        //dirección del remitente 
        $headers .= "From: SELBOTPRO - Nuevo Password <".MAIL_PASSWORD_RECOVERY.">\r\n";

        mail($destinatario, $asunto, $cuerpo, $headers);
    }
}