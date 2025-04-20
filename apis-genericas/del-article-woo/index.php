<?php

/**
 * 
*/

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('DEL_ARTICLE_WOO_KEY')) {
    die('Acceso denegado.');
}

$id = $_POST['id'];

// URL de la API de WooCommerce y las claves de acceso
$url_api = $_POST['url_api'] . '/' . $id. '?force=true';
$clave_consumidor = $_POST['consumer_key'];
$clave_secreta = $_POST['consumer_secret'];

// Configurar la solicitud POST con cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Basic " . base64_encode($clave_consumidor . ":" . $clave_secreta)
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Realizar la solicitud POST
$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo $url_api;
// Cerrar la conexión cURL
curl_close($ch);

// Verificar la respuesta
if ($http_status == 201) {
    $response = json_decode($response, true);
    $response = [
        'id' => $response['id'],
        'link' => $response['permalink'],
    ];

    $response = json_encode($response);
    //echo $response;
    echo $url_api;
} else {
    echo $url_api;
    echo "Error al insertar el artículo: " . $response;
}
