<?php

//envía el resultado final al index
class LogDb {

    //crear tabla en mysql si no existe
    static public function createTable() {
        $conn = ServiceDb::connect();

        $sql = "CREATE TABLE IF NOT EXISTS log_bot (
            LO_id_ INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            LO_admin_phone_ VARCHAR(255) NULL,
            LO_user_phone_ VARCHAR(255) NULL,
            LO_user_text_ LONGTEXT NULL,
            LO_adin_response_ LONGTEXT NULL,
            LO_estado_ VARCHAR(255) NULL,
            timestamp TIMESTAMP
        ) ";

        if (!mysqli_query($conn, $sql)) {
            echo "Error al crear la tabla log_bot: " . mysqli_error($conn);
        }
    }

    static public function postLog($requests, $respuesta, $userId){
        $conn = ServiceDb::connect();

        $adminPhone = $requests['adminPhone'];
        $userPhone  = $requests['userPhone'];
        $text       = mysqli_escape_string($conn, $requests['text']);
        $estado     = mysqli_escape_string($conn, SesionDb::getInformeEstado($userId, $adminPhone));

        $sql = "INSERT INTO log_bot (
            LO_admin_phone_,
            LO_user_phone_,
            LO_user_text_,
            LO_adin_response_,
            LO_estado_
        ) VALUES (
            '$adminPhone',
            '$userPhone',
            '$text',
            '$respuesta',
            '$estado'
        )";
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error al añadir un nuevo log: " . $sql . "<br>" . mysqli_error($conn).'<br>';
        }
    }

}