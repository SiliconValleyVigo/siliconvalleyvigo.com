<?php

/**
 * 
*/

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('POST_ARTICLE_WOO_KEY')) {
    die('Acceso denegado.');
}

// Datos del nuevo artículo
$nombre = $_POST['name'];
$precio = $_POST['price'];
$descripcion = $_POST['description'];
$imagen_url = $_POST['image_url'];
$precioDescuento = $_POST['sale_price'];
$categoria = intval($_POST['category']);
$stock = intval($_POST['stock']);
$coste = $_POST['coste'];

// URL de la API de WooCommerce y las claves de acceso
$url_api = $_POST['url_api'];
$clave_consumidor = $_POST['consumer_key'];
$clave_secreta = $_POST['consumer_secret'];

// Datos para la solicitud POST
$data = [
    "name" => $nombre,
    "regular_price" => $precio,
    "sale_price" => $precioDescuento,
    "description" => $descripcion,
    "manage_stock" => true,
    "stock_quantity" => $stock,
    "categories" => [
        [
            "id" => $categoria
        ]
    ],
    "images" => [
        [
            "src" => $imagen_url
        ]
    ],
	'attributes' => [
        [
            'name' => 'Coste',
            'position' => 0,
            'visible' => false,
            'variation' => true,
            'options' => [$coste]
        ]
    ]
];

// Configurar la solicitud POST con cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Basic " . base64_encode($clave_consumidor . ":" . $clave_secreta)
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Realizar la solicitud POST
$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

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
    echo $response;
} else {
    echo "Error al insertar el artículo: " . $response;
}
