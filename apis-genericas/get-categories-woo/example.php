<?php
/**
 * 
*/

$url = 'https://siliconvalleyvigo.com/apis-genericas/get-categories-woo/';
$clave = getenv('GET_CATEGORIES_WOO_KEY');

// URL de la API de WooCommerce y las claves de acceso
$url_api = 'https://mueblesantiguos.siliconvalleyvigo.com/wp-json/wc/v3/products/categories';
$clave_consumidor = getenv('GET_CATEGORIES_WOO_CONSUMER_KEY');
$clave_secreta = getenv('GET_CATEGORIES_WOO_CONSUMER_SECRET');

// Configuración de la solicitud curl
$opciones = array(
  CURLOPT_URL => $url,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => array(
    'key' => $clave,
    'url_api' => $url_api,
    'consumer_key' => $clave_consumidor,
    'consumer_secret' => $clave_secreta
  )
);

// Iniciar la solicitud curl
$curl = curl_init();
curl_setopt_array($curl, $opciones);

// Ejecutar la solicitud curl
$resultado = curl_exec($curl);

//$resultado = substr($resultado, 0, strpos($resultado, "]") + 1);

//echo $resultado;
//echo $resultado;