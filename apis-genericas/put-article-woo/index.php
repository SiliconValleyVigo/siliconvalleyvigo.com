<?php

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('PUT_ARTICLE_WOO_KEY')) {
    die('Acceso denegado.');
}

// Datos del nuevo artículo
$nombre = $_POST['name'];
$precio = $_POST['price'];
$descripcion = $_POST['description'];
$imagen_url = $_POST['image_url'];
$precioDescuento = $_POST['sale_price'];
$categoria = intval($_POST['category']);
$id = $_POST['id'];
$coste = $_POST['coste'];
$stock = intval($_POST['stock']);

// URL de la API de WooCommerce y las claves de acceso
$url_api = $_POST['url_api'] . '/' . $id;
$clave_consumidor = $_POST['consumer_key'];
$clave_secreta = $_POST['consumer_secret'];

////////////////////////IMAGENES////////////////////////
// Obtén los datos de la API de WooCommerce a través de un formulario POST
// Construye el encabezado de autenticación
$credenciales = base64_encode("$clave_consumidor:$clave_secreta");
$encabezados = array(
    'Authorization: Basic ' . $credenciales,
);

// Inicia una sesión cURL
$ch = curl_init($url_api);

// Configura las opciones de la solicitud cURL
curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Realiza la solicitud GET
$respuesta = curl_exec($ch);

// Cierra la sesión cURL
curl_close($ch);

// Verifica si la solicitud fue exitosa
if ($respuesta !== false) {
    $producto = json_decode($respuesta, true);

    if (isset($producto['images'])) {
        $imagenes_producto = $producto['images'];

        // Itera a través de las imágenes y muestra sus URLs
        $imagenesLista = [];
        foreach ($imagenes_producto as $imagen) {
            $imagenesLista[] = ["src" => $imagen['src']];
        }

        //si imagen_url no está vacío y no es "no" o "No" o "NO" o null o false o undefined eliminar la primera imagen de la lista y añadir imagen_url a la lista de imagenes
        if ($imagen_url != '' && !in_array(strtolower($imagen_url), ['no', null, 'false', 'undefined'])) {
            array_shift($imagenesLista);

            //añadir imagen_url a la lista de imagenes en la primera posición
            array_unshift($imagenesLista, ["src" => $imagen_url]);
        }
    } else {
        $imagenesLista = [["src" => $imagen_url]];
    }
} else {
    echo "Error al realizar la solicitud a la API.";
    exit;
}

///////////////////////////////////////////////


// Datos para la solicitud POST
$data = [
    "name" => $nombre,
    "regular_price" => $precio,
    "sale_price" => $precioDescuento,
    "manage_stock" => true,
    "stock_quantity" => $stock,
    "description" => $descripcion,
    "categories" => [
        [
            "id" => $categoria
        ]
    ],
    "images" => $imagenesLista,
    'attributes' => [
        [
            'name' => 'Coste',
            'position' => 0,
            'visible' => false,
            'variation' => true,
            'options' => [$coste]
        ]
    ],
    "method" => "PUT"
];

// Configurar la solicitud POST con cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
if ($http_status == 200) {
    $response = json_decode($response, true);
    $response = [
        'id' => $response['id'],
        'link' => $response['permalink'],
    ];

    $response = json_encode($response);
    echo $response;
    //echo $url_api;
} else {
    echo $url_api;
    echo "Error al insertar el artículo: " . $response;
}