<?php
//mostrar los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('SOCIAL_POST_KEY')) {
    die('Acceso denegado.');
}

$idPageFacebook = $_POST['idPageFacebook'];
$accessTokenFacebook = $_POST['accessTokenFacebook'];
$idPageInstagram = $_POST['idPageInstagram'];
$accessTokenInstagram = $_POST['accessTokenInstagram'];
$img = $_POST['img'];
$text = urlencode($_POST['text']);

//context post
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
    )
);
$context  = stream_context_create($options);

$finalResponse = array();

if(isset($_POST['idPageFacebook']) && $idPageFacebook != '') {
    $facebookUrl = "https://graph.facebook.com/v16.0/$idPageFacebook/feed?message=$text&link=$img&access_token=$accessTokenFacebook";

    try {
        $response = file_get_contents($facebookUrl, false, $context);
        $finalResponse['facebook'] = true;
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        $finalResponse['facebook'] = false;
    }
}

if(isset($_POST['idPageInstagram']) && $idPageInstagram != '') {
    //publicar la imagen y el texto en instagram
    $intagramPost = "https://graph.facebook.com/v16.0/$idPageInstagram/media?image_url=$img&caption=$text&access_token=$accessTokenInstagram";
    
    try {
        //obtener el id del post
        $responseInstagramPost = file_get_contents($intagramPost, false, $context);
        $responseInstagramPost = json_decode($responseInstagramPost, true);
        $idPost = $responseInstagramPost['id'];

        //publicar el post
        $instagramUrl = "https://graph.facebook.com/v16.0/$idPageInstagram/media_publish?creation_id=$idPost&access_token=$accessTokenInstagram";
        try{
            $response = file_get_contents($instagramUrl, false, $context);
            $finalResponse['instagram'] = true;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            $finalResponse['instagram'] = false;
        }
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        $finalResponse['instagram'] = false;
    }
}

echo json_encode($finalResponse);