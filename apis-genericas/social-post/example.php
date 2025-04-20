<?php

// URL del script que se va a llamar
$url = 'https://siliconvalleyvigo.com/apis-genericas/social-post/';

// Datos que se van a enviar por POST
$data = array(
    'key' => getenv('SOCIAL_POST_KEY'),
    'idPageFacebook' => getenv('SOCIAL_POST_FACEBOOK_ID'),
    'accessTokenFacebook' => getenv('SOCIAL_POST_FACEBOOK_TOKEN'),
    'idPageInstagram' => getenv('SOCIAL_POST_INSTAGRAM_ID'),
    'accessTokenInstagram' => getenv('SOCIAL_POST_INSTAGRAM_TOKEN'),
    'img' => 'https://upload.wikimedia.org/wikipedia/commons/9/9b/THIEL_619.jpg',
    'text' => 'Este es mi mensaje para las redes sociales.'
);

// Configuración de la petición
$options = array(
    'http' => array(
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

// Crear el contexto HTTP
$context = stream_context_create($options);

// Ejecutar la petición y obtener la respuesta
$response = file_get_contents($url, false, $context);

// Imprimir la respuesta
echo $response;
