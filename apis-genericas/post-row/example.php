<?php

// Datos para la solicitud
$url = 'https://siliconvalleyvigo.com/apis-genericas/post-row/'; // Reemplaza con la URL de tu archivo PHP
$clave = getenv('POST_ROW_KEY'); // Reemplaza con la clave que estableciste en tu archivo PHP
$servidor = getenv('DB_SERVER_DB_HOST'); // Reemplaza con la dirección de tu servidor de base de datos
$usuario = getenv('POST_ROW_DB_USER'); // Reemplaza con el usuario de tu base de datos
$contraseña = getenv('POST_ROW_DB_PASSWORD'); // Reemplaza con la contraseña de tu base de datos
$base_datos = getenv('POST_ROW_DB_NAME'); // Reemplaza con el nombre de tu base de datos
$datos = array(
  'columna_1' => 'valor_1',
  'columna_2' => 'valor_2',
  'columna_3' => 'valor_3'
); // Reemplaza con los datos que deseas insertar

// Configuración de la solicitud curl
$opciones = array(
  CURLOPT_URL => $url,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => array(
    'key' => $clave,
    'server' => $servidor,
    'user' => $usuario,
    'pass' => $contraseña,
    'db' => $base_datos,
    'data' => json_encode($datos),
    'table' => 'auto_tabla'
  )
);

// Iniciar la solicitud curl
$curl = curl_init();
curl_setopt_array($curl, $opciones);

// Ejecutar la solicitud curl
$resultado = curl_exec($curl);
if ($resultado === false) {
  die('Error al hacer la solicitud: ' . curl_error($curl));
}


// Procesar la respuesta
if ($resultado === true) {
  echo 'Datos insertados correctamente';
} else {
  echo 'Error al insertar datos: ' . $resultado;
}

// Cerrar la solicitud curl
curl_close($curl);
