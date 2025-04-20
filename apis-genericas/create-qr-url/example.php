<?php
// Datos de entrada
$key = getenv('CREATE_QR_KEY'); // La clave para verificar el acceso
$texto = "";

// Construir la consulta
$url = 'https://siliconvalleyvigo.com/apis-genericas/create-qr-url/'; // Reemplaza con la URL correcta
$data = [
    'key' => $key,
    'texto' => $texto,
    'url' => 'Otro texto cualquiera que no es una url\ny tiene salto de linea'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);


// Manejar la respuesta
if ($result !== false) {
    echo 'URL del archivo generado: ' . $result;
} else {
    echo 'Error al enviar la consulta PHP.' . $result;
}