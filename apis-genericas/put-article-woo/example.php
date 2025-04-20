<?php
/**
 * 
*/

$url = 'https://siliconvalleyvigo.com/apis-genericas/put-article-woo/';
$clave = getenv('PUT_ARTICLE_WOO_KEY');

// Datos del nuevo artículo
$nombre = 'Artículo de prueba MODIFICADO';
$precio = '10,00';
$precioDescuento = '5,00';
$descripcion = 'Descripción del artículo de prueba';
$imagen_url = 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';

// URL de la API de WooCommerce y las claves de acceso
$url_api = 'https://mueblesantiguos.siliconvalleyvigo.com/wp-json/wc/v3/products';
$clave_consumidor = getenv('PUT_ARTICLE_WOO_CONSUMER_KEY');
$clave_secreta = getenv('PUT_ARTICLE_WOO_CONSUMER_SECRET');

// Configuración de la solicitud curl
$opciones = array(
  CURLOPT_URL => $url,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => array(
    'key' => $clave,
    'name' => $nombre,
    'price' => $precio,
    'sale_price' => $precioDescuento,
    'description' => $descripcion,
    'image_url' => $imagen_url,
    'url_api' => $url_api,
    'consumer_key' => $clave_consumidor,
    'consumer_secret' => $clave_secreta,
    'id' => '201'
  ),
  CURLOPT_RETURNTRANSFER => true
);

// Iniciar la solicitud curl
$curl = curl_init();
curl_setopt_array($curl, $opciones);

// Ejecutar la solicitud curl
$resultado = curl_exec($curl);

curl_close($curl);
echo $resultado;