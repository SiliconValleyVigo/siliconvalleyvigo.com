<?php
// Datos de entrada
$key = getenv('CREATE_EXCEL_KEY'); // La clave para verificar el acceso
$fileData = array(
    array('Nombre', 'Edad', 'Correo'),
    array('Juan', 25, 'juan@example.com'),
    array('María', 30, 'maria@example.com')
);

// Construir la consulta
$url = 'https://siliconvalleyvigo.com/apis-genericas/create-excel-url/'; // Reemplaza con la URL correcta
$data = array(
    'key' => $key,
    'file' => $fileData
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

// Manejar la respuesta
if ($result !== false) {
    echo 'URL del archivo generado: ' . $result;
} else {
    echo 'Error al enviar la consulta PHP.';
}