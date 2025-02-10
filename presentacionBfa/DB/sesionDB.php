<?php

class SesionDb {

//crear tabla en mysql si no existe
    static public function createTable() {
        $conn = ServiceDb::connect();

        $sql = "CREATE TABLE IF NOT EXISTS sesion_bot (
            SE_id_ INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            SE_user_id VARCHAR(32) NULL,
            SE_admin_telefono VARCHAR(32) NULL,
            SE_servicio_id VARCHAR(32) NULL,
            SE_estado_id VARCHAR(32) NULL,
            SE_opcion_id VARCHAR(32) NULL,
            SE_campo_id VARCHAR(32) NULL,
            SE_json LONGTEXT NULL,
            timestamp TIMESTAMP
        )";

        if (!mysqli_query($conn, $sql)) {
            echo "Error al crear la tabla user_bot: " . mysqli_error($conn);
        }
    }

    //Añadir columnas extra a la DB a partir de un array en confi
    static public function createColumnsExtra(){
        if(count(ARRAY_SESION_COLUMNS) !== 0){
            $conn = ServiceDb::connect();
            $consulta = "";
            foreach (ARRAY_SESION_COLUMNS as $column){
                $consulta .= "ADD COLUMN IF NOT EXISTS ". $column['name'] ." ". $column['sql'].",";
            }
            $consulta = rtrim($consulta, ',');

            $sql = "ALTER TABLE sesion_bot $consulta";
            if (!mysqli_query($conn, $sql)) {
                echo "Error al añadir Columnas sesion_bot: " . mysqli_error($conn);
            }
        }
    }

    //Insertar un usuario nuevo
    static public function createReadSesion($userId, $adminPhone) {
        $conn = ServiceDb::connect();
        $sql = "INSERT INTO sesion_bot (
                SE_user_id,
                SE_admin_telefono) 
                SELECT 
                '$userId', 
                '$adminPhone'
                FROM DUAL WHERE NOT EXISTS (
                    SELECT * FROM sesion_bot 
                    WHERE SE_user_id='$userId' AND
                          SE_admin_telefono='$adminPhone'
                    LIMIT 1
                )";
        if (mysqli_query($conn, $sql)) {
            $inicio = self::puntoInicio();
            if($inicio === 'estado'){
                $datos = ['SE_servicio_id' => 1];
                self::updateSesion($userId, $adminPhone, $datos);
            }
            if($inicio === 'opcion'){
                $datos = [
                    'SE_servicio_id' => 1,
                    'SE_estado_id' => 1
                ];
                self::updateSesion($userId, $adminPhone, $datos);
            }
            return self::readSesion($userId, $adminPhone);
        } else {
            echo "Error al añadir un nuevo user: " . $sql . "<br>" . mysqli_error($conn).'<br>';
        }
    }

    static public function readSesion($userId, $adminPhone){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM sesion_bot WHERE 
                SE_user_id = '$userId' AND
                SE_admin_telefono = '$adminPhone'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }else {
            $row = false;
        }
        return $row;
    }

    static public function updateSesion($userId, $adminPhone, $datos){
        $conn = ServiceDb::connect();

        $consulta = "";
        foreach ($datos as $key => $value){
            $value = mysqli_escape_string($conn, $value);
            if($value === 'NULL'){
                //si key es igual a 0 es xq no se puede volver más atrás en el array por lo tanto no se debe ejecutar la consulta.
                if($key === 0){
                    $consulta = false;
                }else{
                    $consulta .= $key ." = NULL,";
                }
            }else{
                $consulta .= $key ." = '". $value ."',";
            }  
        }
        if($consulta !== false){
            $consulta = rtrim($consulta, ',');
        
            $sql = "UPDATE sesion_bot SET
                    $consulta
                    WHERE
                        SE_user_id = '$userId' AND
                        SE_admin_telefono = '$adminPhone' ";
            if (mysqli_query($conn, $sql)) {
                return true;
            } else {
                echo "Error al actualizar el admin: " . $sql . "<br>" . mysqli_error($conn).'<br>';
            }
        }else{
            return true;
        }
    }

    static public function puntoInicio(){
        $nServicios = [];
        $arrayEstados   = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            $nServicios[] = $servicio['id_servicio'];
            $arrayEstados[] = $servicio['array_estados'];
        }

        $inicio = false;

        $nEstados = [];
        if(count($nServicios) == 1){
            $inicio = 'estado';
            foreach($arrayEstados[0] as $estado){
                $nEstados[] = $estado['id_estado'];
            }
        }

        if(count($nEstados) == 1){
            $inicio = 'opcion';
        }
        
        return $inicio;
    }

    static public function getInformeEstado($userId, $adminPhone){
        $d = SesionDb::readSesion($userId, $adminPhone);
        return "0".$d['SE_servicio_id']." | 0".$d['SE_estado_id']." | 0".$d['SE_opcion_id']." | 0".$d['SE_campo_id'];
    }

    static public function updateElement($userId, $adminPhone, $column, $value){
        $conn = ServiceDb::connect();
        $value = mysqli_escape_string($conn, $value);

        $sql = "UPDATE sesion_bot SET $column = '$value' WHERE SE_user_id = '$userId' AND SE_admin_telefono = '$adminPhone' ";

        if (mysqli_query($conn, $sql)) {
            $response = true;
        } else {
            $response = false;
        }

        return $response;
    }

    static public function getElement($userId, $adminPhone, $column){
        $conn = ServiceDb::connect();

        $sql = "SELECT $column FROM sesion_bot WHERE SE_user_id = '$userId' AND SE_admin_telefono = '$adminPhone'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $element = mysqli_fetch_assoc($result);
        }else {
            $element = false;
        }

        return $element;
    }
}