<?php

$key = $_POST['key'];
// Recibe la clave para verificar que el código se puede ejecutar
if ($key != getenv('POST_ROW_KEY')) {
    die('Acceso denegado.');
}

$server = $_POST['server'];
$user = $_POST['user'];
$pass = $_POST['pass'];
$db = $_POST['db'];
$data = $_POST['data'];
$table = $_POST['table'];

// Conectar a la base de datos
$conexion = mysqli_connect($server, $user, $pass, $db);
if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Decodificar el JSON enviado y preparar los datos para insertar
$datos = json_decode($data, true);
$columnas = array_keys($datos);
$filas = array_values($datos);

// Crear tabla si no existe
$nombre_tabla = $table;
$crear_tabla_sql = "CREATE TABLE IF NOT EXISTS $nombre_tabla (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,";
foreach ($columnas as $columna) {
    $crear_tabla_sql .= "$columna VARCHAR(255),";
}
$crear_tabla_sql = rtrim($crear_tabla_sql, ',') . ")";
if (!mysqli_query($conexion, $crear_tabla_sql)) {
    die('Error al crear la tabla: ' . mysqli_error($conexion));
}

// Verificar si las columnas existen y agregarlas si no
$describir_tabla_sql = "DESCRIBE $nombre_tabla";
$columnas_existentes = array();
$resultado = mysqli_query($conexion, $describir_tabla_sql);
while ($fila = mysqli_fetch_assoc($resultado)) {
    $columnas_existentes[] = $fila['Field'];
}
foreach ($columnas as $columna) {
    if (!in_array($columna, $columnas_existentes)) {
        $agregar_columna_sql = "ALTER TABLE $nombre_tabla ADD $columna VARCHAR(30)";
        if (!mysqli_query($conexion, $agregar_columna_sql)) {
            die('Error al agregar columna: ' . mysqli_error($conexion));
        }
    }
}

// Insertar los datos
$insertar_sql = "INSERT INTO $nombre_tabla (";
foreach ($columnas as $columna) {
    $insertar_sql .= "$columna,";
}
$insertar_sql = rtrim($insertar_sql, ',') . ") VALUES (";
foreach ($filas as $fila) {
    $insertar_sql .= "'$fila',";
}
$insertar_sql = rtrim($insertar_sql, ',') . ")";
$insertar_sql = rtrim($insertar_sql, ',');

if (!mysqli_query($conexion, $insertar_sql)) {
    die('Error al insertar datos: ' . mysqli_error($conexion));
}


// Devolver true si se insertaron los datos
if (mysqli_affected_rows($conexion) > 0) {
    echo 'true';
} else {
    echo 'Error al insertar datos';
}

// Cerrar la conexión
mysqli_close($conexion);
