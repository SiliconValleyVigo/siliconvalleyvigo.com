<?php

$key = $_POST['key'];

// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('CREATE_QR_KEY')) {
    die('Acceso denegado.');
}

$texto = $_POST['texto'];
$url = $_POST['url'];

include('phpqrcode/qrlib.php');

function generarQRConTexto($texto, $url) {
    $archivo_imagen = 'codigo_qr.png';
    $tamaño = 15; // Tamaño en píxeles

    QRcode::png($url, $archivo_imagen, QR_ECLEVEL_L, $tamaño);

    // Crear una imagen en blanco para combinar el QR Code y el texto
    $imagenFinal = imagecreatetruecolor(2000, 1000);

    // Establecer el fondo de la imagen en blanco
    $colorFondo = imagecolorallocate($imagenFinal, 255, 255, 255);
    imagefill($imagenFinal, 0, 0, $colorFondo);

    // Crear una imagen a partir del QR Code
    $qrImagen = imagecreatefrompng($archivo_imagen);

    // Copiar el QR Code en la imagen final
    imagecopy($imagenFinal, $qrImagen, 0, 0, 0, 0, imagesx($qrImagen), imagesy($qrImagen));

// Calcular el tamaño deseado de la imagen con el margen blanco (puedes ajustar este valor)
$margenBlanco = 50;
$nuevaAnchura = imagesx($imagenFinal) + 2 * $margenBlanco;
$nuevaAltura = imagesy($imagenFinal) + 2 * $margenBlanco;

// Asegurarse de que la imagen tenga el tamaño deseado (más ancho que alto)
if ($nuevaAnchura < $nuevaAltura) {
    $nuevaAnchura = $nuevaAltura;
}

// Crear una nueva imagen con el tamaño deseado y fondo blanco
$nuevaImagen = imagecreatetruecolor($nuevaAnchura, $nuevaAltura);
$colorFondo = imagecolorallocate($nuevaImagen, 255, 255, 255);
imagefill($nuevaImagen, 0, 0, $colorFondo);

// Copiar la imagen con el QR Code y el texto en la nueva imagen con el margen
imagecopy($nuevaImagen, $imagenFinal, $margenBlanco, $margenBlanco, 0, 0, imagesx($imagenFinal), imagesy($imagenFinal));

    // Establecer el color del texto en negro
    $colorTexto = imagecolorallocate($imagenFinal, 0, 0, 0);

    // Establecer la fuente del texto (cambia la ruta según tus necesidades)
    $fuente = './RobotoCondensed-Bold.ttf';

    // Establecer el tamaño del texto
    $tamanioTexto = 55;

    // Establecer la posición del texto
    $posicionX = imagesx($qrImagen) + 10;
    $posicionY = imagesy($qrImagen) / 3;

    // Dividir el texto en líneas utilizando los saltos de línea "_" en el texto
    $lineasTexto = explode('_', $texto);

    // Escribir el texto en la imagen final
    foreach ($lineasTexto as $indice => $linea) {
        $posicionYTexto = $posicionY + ($indice * $tamanioTexto * 1.3);
        $linea = substr($linea, 0, 11);
        imagettftext($imagenFinal, $tamanioTexto, 0, $posicionX, $posicionYTexto, $colorTexto, $fuente, $linea);
    }

    $imagenFinal = imagecropauto($imagenFinal, IMG_CROP_WHITE);

// Calcular el tamaño deseado de la imagen con el margen blanco (puedes ajustar este valor)
$margenBlanco = 50;
$nuevaAnchura = imagesx($imagenFinal) + 2 * $margenBlanco;
$nuevaAltura = imagesy($imagenFinal) + 2 * $margenBlanco;

// Crear una nueva imagen con el tamaño deseado y fondo blanco
$nuevaImagen = imagecreatetruecolor($nuevaAnchura, $nuevaAltura);
$colorFondo = imagecolorallocate($nuevaImagen, 255, 255, 255);
imagefill($nuevaImagen, 0, 0, $colorFondo);

// Copiar la imagen con el QR Code y el texto en la nueva imagen con el margen
imagecopy($nuevaImagen, $imagenFinal, $margenBlanco, $margenBlanco, 0, 0, imagesx($imagenFinal), imagesy($imagenFinal));

// Calcular el tamaño de la imagen escalada sin distorsión
$anchoEscalado = 234;
$altoEscalado = 132;
$ratioOriginal = imagesx($nuevaImagen) / imagesy($nuevaImagen);
$ratioEscalado = $anchoEscalado / $altoEscalado;

if ($ratioOriginal > $ratioEscalado) {
    $anchoFinal = $anchoEscalado;
    $altoFinal = $anchoFinal / $ratioOriginal;
} else {
    $altoFinal = $altoEscalado;
    $anchoFinal = $altoFinal * $ratioOriginal;
}

// Crear una nueva imagen escalada sin distorsión
$imagenEscalada = imagecreatetruecolor($anchoFinal, $altoFinal);
imagecopyresampled($imagenEscalada, $nuevaImagen, 0, 0, 0, 0, $anchoFinal, $altoFinal, imagesx($nuevaImagen), imagesy($nuevaImagen));
	
// Color del rectángulo (en RGB)
$colorRectangulo = imagecolorallocate($imagenEscalada, 0, 0, 0); // Verde

// Coordenadas del rectángulo
$x1 = 132; // Esquina superior izquierda, eje X
$y1 = 0;  // Esquina superior izquierda, eje Y
$x2 = 131; // Esquina inferior derecha, eje X
$y2 = 234; // Esquina inferior derecha, eje Y

// Dibujar el rectángulo
imagerectangle($imagenEscalada, $x1, $y1, $x2, $y2, $colorRectangulo);

// Obtener el dominio completo
$dominio = obtenerDominioCompleto();

// Generar una ruta única para guardar la imagen
$rutaImagenFinal = 'files/' . 'codigo_qr_' . uniqid() . '.png';

// Guardar la imagen escalada sin distorsión en un archivo
imagepng($imagenEscalada, $rutaImagenFinal);

// Liberar memoria
imagedestroy($imagenFinal);
imagedestroy($nuevaImagen);
imagedestroy($imagenEscalada);

// Devolver la URL completa de la imagen generada
return $dominio . '/' . $rutaImagenFinal;
}

// Función para obtener el dominio completo
function obtenerDominioCompleto()
{
    $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $dominio = $_SERVER['HTTP_HOST'];
    return $protocolo . $dominio . '/apis-genericas/create-qr-url';
}

// Ejemplo de uso
$urlImagen = generarQRConTexto($texto, $url);
echo $urlImagen;
