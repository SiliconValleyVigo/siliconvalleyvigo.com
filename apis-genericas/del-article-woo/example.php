<?php
/**
 * 
*/

$url = 'https://siliconvalleyvigo.com/apis-genericas/del-article-woo/';
$clave = getenv('DEL_ARTICLE_WOO_KEY');

// URL de la API de WooCommerce y las claves de acceso
$url_api = 'https://mueblesantiguos.siliconvalleyvigo.com/wp-json/wc/v3/products';
$clave_consumidor = getenv('DEL_ARTICLE_WOO_CONSUMER_KEY');
$clave_secreta = getenv('DEL_ARTICLE_WOO_CONSUMER_SECRET');

// Configuración de la solicitud curl
$opciones = array(
  CURLOPT_URL => $url,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => array(
    'key' => $clave,
    'url_api' => $url_api,
    'consumer_key' => $clave_consumidor,
    'consumer_secret' => $clave_secreta,
    'id' => '197'
  )
);

// Iniciar la solicitud curl
$curl = curl_init();
curl_setopt_array($curl, $opciones);

// Ejecutar la solicitud curl
$resultado = curl_exec($curl);
echo $resultado;