<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('GET_DATA_KEY')) {
    die('Acceso denegado.');
}

$server = $_POST['server'] ?? getenv('DB_SERVER_DB_HOST');
$user = $_POST['user'] ?? getenv('GET_DATA_DB_USER');
$pass = $_POST['pass'] ?? getenv('GET_DATA_DB_PASSWORD');
$db = $_POST['db'] ?? getenv('GET_DATA_DB_NAME');
$search = $_POST['search'];
$table = $_POST['table'] ?? 'auto_tabla';

// Conecta a la base de datos
$conexion = mysqli_connect($server, $user, $pass, $db);

// Verifica si la conexión es exitosa
if (!$conexion) {
    die('Error al conectar: ' . mysqli_connect_error());
}

// Si el parametro opcional no existe o está vacío, devuelve todo el contenido de una tabla
if ($search == false || $search == 'false') {
    $query = "SELECT * FROM $table";
}
// Si el parametro opcional contiene un string, buscar ese string en toda la tabla y devolver las filas completas que contengan ese string en cualquiera de sus campos
else {
    // Obtenemos la lista de columnas de la tabla
    $columns_query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table'";
    $columns_result = mysqli_query($conexion, $columns_query);
    $columnas = array();
    while ($columna = mysqli_fetch_assoc($columns_result)) {
        $columnas[] = $columna['COLUMN_NAME'];
    }

    $columnasConBusqueda = "";
    foreach ($columnas as $columna) {
        $columnasConBusqueda .= "$columna LIKE '%$search%' OR ";
    }

    $columnasConBusqueda = substr($columnasConBusqueda, 0, -4);

    // Construimos la consulta SQL dinámicamente
    $query = "SELECT * FROM $table WHERE $columnasConBusqueda";
}

$resultado = mysqli_query($conexion, $query);

// Verifica si la consulta es exitosa
if (!$resultado) {
    die('Error al consultar: ' . mysqli_error($conexion));
}

// Crea un arreglo para almacenar los resultados de la consulta
$resultados = array();

// Almacena los resultados en el arreglo
while ($fila = mysqli_fetch_assoc($resultado)) {
    $resultados[] = $fila;
}

// Devuelve los resultados en formato JSON
echo json_encode($resultados);

// Cierra la conexión a la base de datos
mysqli_close($conexion);