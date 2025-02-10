<?php

$destinatario = getenv('MAIL_FORM');
$remitente = $_POST['remitente'];
$asunto = $_POST['asunto'];
$mensaje = $_POST['mensaje'];

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

//dirección del remitente 
$headers .= "From: $remitente <".$remitente.">\r\n";

$enviado = mail($destinatario, $asunto, $mensaje, $headers);

if ($enviado) {
    echo json_encode(["status" => "success", "message" => "Mensaje enviado correctamente"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al enviar el mensaje"]);
}