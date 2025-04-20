<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$key = $_POST['key'];

// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('CREATE_EXCEL_KEY')) {
    die('Acceso denegado.');
}

$data = $_POST['file'];

function generarDocumentoExcel($data) {
    // Crear una nueva instancia de la clase Spreadsheet
    $spreadsheet = new Spreadsheet();

    // Obtener la hoja de cálculo activa
    $sheet = $spreadsheet->getActiveSheet();

    // Establecer los valores de los datos
    $valores = [];
    foreach ($data as $objeto) {
        $valores[] = array_values((array)$objeto);
    }
    $sheet->fromArray($valores, null, 'A1');

    // Ajustar el ancho de las columnas al contenido
    foreach(range('A', $sheet->getHighestDataColumn()) as $columna) {
        $sheet->getColumnDimension($columna)->setAutoSize(true);
    }

    // Crear un objeto Writer para guardar el archivo en formato XLSX
    $writer = new Xlsx($spreadsheet);

    // Guardar el documento en el directorio ./files con un nombre único
    $rutaArchivo = "./files/documento_excel_" . uniqid() . ".xlsx";
    $writer->save($rutaArchivo);

    // Devolver la URL completa del archivo generado
    $urlCompleta = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/files/' . basename($rutaArchivo);
    return $urlCompleta;
}

$resultado = generarDocumentoExcel($data);
echo $resultado;
