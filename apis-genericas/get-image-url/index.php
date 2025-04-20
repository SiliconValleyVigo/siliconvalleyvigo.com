<?php
$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('GET_IMAGE_KEY')) {
    die('Acceso denegado.');
}

$base64_file = $_POST['file'];
$file_type = $_POST['type'];


// Decodificamos el contenido en base64 a bytes
$file_data = base64_decode($base64_file);

// Generamos un nombre único para el fichero usando un timestamp y la extensión del archivo original
$file_name = time() . '.' . $file_type;

// Definimos la ruta donde se guardará el fichero
$file_path = 'files/' . $file_name;

// Guardamos el fichero en disco
file_put_contents($file_path, $file_data);

// Obtenemos la URL del fichero
$file_url = 'https://' . $_SERVER['HTTP_HOST'] . '/' . 'apis-genericas/get-image-url/' . $file_path;

// Devolvemos la URL del fichero
echo $file_url;
