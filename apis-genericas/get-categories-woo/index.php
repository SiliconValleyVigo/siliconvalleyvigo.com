<?php

/**
 * 
*/

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('GET_CATEGORIES_WOO_KEY')) {
    die('Acceso denegado.');
}

// URL de la API REST de WooCommerce
$api_url = $_POST['url_api'];

// Claves de autenticación de la API (obtenidas desde la sección de API Keys en la configuración de WooCommerce)
$consumer_key = $_POST['consumer_key'];
$consumer_secret = $_POST['consumer_secret'];

// Construye la URL con las claves de autenticación
$auth_url = $api_url . '?consumer_key=' . $consumer_key . '&consumer_secret=' . $consumer_secret. '&per_page=100';

// Inicializa cURL
$ch = curl_init();

// Configura las opciones de cURL
curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactiva la verificación SSL (solo para pruebas, no recomendado en producción)

// Ejecuta la solicitud a la API REST
$response = curl_exec($ch);

// Cierra la conexión cURL
curl_close($ch);

// Decodifica la respuesta JSON
$categories = json_decode($response, true);

// Verifica si hubo un error en la respuesta
if (isset($categories['code'])) {
    echo 'Error: ' . $categories['message'];
} else {
    // Imprime las categorías

    $nameAndIdCategories = [];
    foreach ($categories as $category) {
        $nameAndIdCategories[] = [
            'name' => $category['name'],
            'id' => $category['id']
        ];
    }

    $result = json_encode($nameAndIdCategories);
    
    //eliminar todos los caracteres después de ]
    $result = substr($result, 0, strpos($result, "]") + 1);

    echo $result;
}
