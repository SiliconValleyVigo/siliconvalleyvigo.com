<?php
//https://siliconvalleyvigo.com/get-data/example.php
// Configura los datos de la solicitud
/*
$data = array(
    'key' => getenv('GET_DATA_KEY'),
    'server' => getenv('DB_SERVER_DB_HOST'),
    'user' => getenv('GET_DATA_DB_USER'),
    'pass' => getenv('GET_DATA_DB_PASSWORD'),
    'db' => getenv('GET_DATA_DB_NAME'),
    'search' => 'Lápiz',
    'table' => 'auto_tabla'
);
*/
$data = array(
    'key' => getenv('GET_DATA_KEY'),
    'search' => 'Boligrafo',
    'table' => 'auto_tabla'
);
// Configura las opciones de la solicitud
$options = array(
    CURLOPT_URL => 'https://siliconvalleyvigo.com/apis-genericas/get-data/',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_RETURNTRANSFER => true
);

// Inicializa cURL y configura las opciones
$curl = curl_init();
curl_setopt_array($curl, $options);

// Ejecuta la solicitud y obtiene la respuesta
$response = curl_exec($curl);

// Verifica si hay errores
if(curl_errno($curl)){
    echo 'Error al hacer la solicitud: ' . curl_error($curl);
}

// Imprime la respuesta de la API
echo $response;

// Cierra la conexión cURL
curl_close($curl);
