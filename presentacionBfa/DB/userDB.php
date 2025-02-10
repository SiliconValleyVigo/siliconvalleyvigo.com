<?php

class UserDb {

//crear tabla en mysql si no existe
    static public function createTable() {
        $conn = ServiceDb::connect();

        $sql = "CREATE TABLE IF NOT EXISTS user_bot (
            US_id_ INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            US_telefono VARCHAR(255) NULL,
            timestamp TIMESTAMP)";

        if (!mysqli_query($conn, $sql)) {
            echo "Error al crear la tabla user_bot: " . mysqli_error($conn);
        }
    }

    //Añadir columnas extra a la DB a partir de un array en confi
    static public function createColumnsExtra(){
        if(count(ARRAY_USER_COLUMNS) !== 0){
            $conn = ServiceDb::connect();
            $consulta = "";
            foreach (ARRAY_USER_COLUMNS as $column){
                $consulta .= "ADD COLUMN IF NOT EXISTS ". $column['name'] ." ". $column['sql'].",";
            }
            $consulta = rtrim($consulta, ',');

            $sql = "ALTER TABLE user_bot $consulta";
            if (!mysqli_query($conn, $sql)) {
                echo "Error al añadir Columnas user_bot: " . mysqli_error($conn);
            }
        }
    }

    //Insertar un usuario nuevo
    static public function createReadUser($userPhone) {
        $conn = ServiceDb::connect();
        $sql = "INSERT INTO user_bot (US_telefono) 
                SELECT '$userPhone'
                FROM DUAL WHERE NOT EXISTS (
                    SELECT * FROM user_bot 
                    WHERE US_telefono='$userPhone'
                    LIMIT 1
                )";
        if (mysqli_query($conn, $sql)) {      
            return self::readUser($userPhone);
        } else {
            echo "Error al añadir un nuevo user: " . $sql . "<br>" . mysqli_error($conn).'<br>';
        }
    }

    static public function readUser($userPhone){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM user_bot WHERE US_telefono = '$userPhone'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }else {
            $row = false;
        }
        return $row;
    }

    static public function updateUser($userId, $datos){
        $conn = ServiceDb::connect();

        $consulta = "";
        foreach ($datos as $key => $value){
            $value = mysqli_escape_string($conn, $value);
            $consulta .= $key ." = '". $value ."',";
        }
        $consulta = rtrim($consulta, ',');
        $consulta = $consulta;
        
        $sql = "UPDATE user_bot SET
                $consulta
                WHERE
                    SE_user_id = '$userId' ";
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error al actualizar el admin: " . $sql . "<br>" . mysqli_error($conn).'<br>';
        }
    }
}