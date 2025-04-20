<?php

//envía el resultado final al index
class AdminDb {

    //crear tabla en mysql si no existe
    static public function createTable() {
        $conn = ServiceDb::connect();

        $sql = "CREATE TABLE IF NOT EXISTS admin_bot (
            AD_id_ INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            AD_email_ VARCHAR(255) NULL,
            AD_password_ VARCHAR(255) NULL,
            AD_tipo_servicio_ VARCHAR(255)  NULL,
            AD_validated_ BOOLEAN NULL,
            AD_nombre_del_servicio__text VARCHAR(255) NULL,
            AD_numero_de_telefono_del_bot__tel VARCHAR(255)  NULL,
            AD_numero_de_telefono_con_permiso_de_administrador__text VARCHAR(255)  NULL,
            AD_prefijo_internacional__number int  NULL,
            timestamp TIMESTAMP
        ) ";

        if (!mysqli_query($conn, $sql)) {
            echo "Error al crear la tabla admin_bot: " . mysqli_error($conn);
        }
    }

    //Añadir columnas extra a la DB a partir de un array en confi
    static public function createColumnsExtra(){
        if(count(ARRAY_ADMIN_COLUMNS) !== 0){
            $conn = ServiceDb::connect();
            $consulta = "";
            foreach (ARRAY_ADMIN_COLUMNS as $column){
                $consulta .= "ADD COLUMN IF NOT EXISTS ". $column['name'] ." ". $column['sql'].",";
            }
            $consulta = rtrim($consulta, ',');

            $sql = "ALTER TABLE admin_bot $consulta";
            if (!mysqli_query($conn, $sql)) {
                echo "Error al añadir Columnas admin_bot: " . mysqli_error($conn);
            }
        }
    }

    static public function readSesion($adminPhone, $servicio){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM sesion_bot WHERE 
                AD_numero_de_telefono_del_bot__tel = '$adminPhone' AND
                AD_tipo_servicio_ = '$servicio' ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }else {
            $row = false;
        }
        return $row;
    }

    //Comprobar si el mail ya existe en la tabla
    static public function checkMail($mail){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM admin_bot WHERE AD_email_ = '$mail'";

        $result = mysqli_query($conn, $sql);

        return mysqli_num_rows($result) > 0;
    }

    //Insertar un usuario nuevo
    static public function postUser($mail, $password, $servicio, $adminPhone, $nombreServicio) {
        $conn = ServiceDb::connect();

        $mailExists = AdminDb::checkMail($mail);

        if($mailExists === true){
            echo json_encode([
                'status' => '400',
                'message' => 'Ya existe un usuario con este mail registrado'
            ]);

            return 'existe';
        }
        if($mailExists === false){    
            $sql = "INSERT INTO admin_bot (
                AD_email_,
                AD_password_,
                AD_tipo_servicio_,
                AD_numero_de_telefono_del_bot__tel,
                AD_nombre_del_servicio__text
            ) VALUES (
                '$mail',
                '$password',
                '$servicio',
                '$adminPhone',
                '$nombreServicio'
            )";
            if (mysqli_query($conn, $sql)) {
                return true;
            } else {
                echo "Error al añadir un nuevo admin: " . $sql . "<br>" . mysqli_error($conn).'<br>';
            }
        }
    }

    //Comprobar si el mail y la contraseña son correctos y devolver los datos de usuario
    static public function checkUser($mail, $password){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM admin_bot WHERE AD_email_ = '$mail' AND AD_password_ = '$password'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

        }else {
            $row = false;
        }

        return $row;
    }

    static public function getRowsUser($mail, $password){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM admin_bot WHERE AD_email_ = '$mail' AND AD_password_ = '$password'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $userRow = mysqli_fetch_assoc($result);
            $adminPhone = $userRow['AD_numero_de_telefono_del_bot__tel'];

            $sql = "SELECT * FROM admin_bot WHERE AD_numero_de_telefono_del_bot__tel = '$adminPhone'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }else {
                $rows = false;
            }
        }else {
            $rows = false;
        }

        return $rows;
    }

    //Comprobar si la contraseña es correcta
    static public function checkPassword($mail, $password){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM admin_bot WHERE AD_email_ = '$mail' AND AD_password_ = '$password'";

        $result = mysqli_query($conn, $sql);

        return mysqli_num_rows($result) > 0;

    }

    //Actualizar un dato
    static public function updatePassword($mail, $password){
        $conn = ServiceDb::connect();

        $mail = mysqli_escape_string($conn, $mail);
        $password = mysqli_escape_string($conn, $password);

        $sql = "UPDATE admin_bot SET
            AD_password_ = '$password'
        WHERE AD_email_ = '$mail'";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error al actualizar el admin: " . $sql . "<br>" . mysqli_error($conn).'<br>';
        }
    }

    static public function updateAdmin($data){
        $conn = ServiceDb::connect();
        
        $AD_id_ = mysqli_escape_string($conn, $data['AD_id_']);
        $AD_email_ = mysqli_escape_string($conn, $data['AD_email_']);
        $AD_nombre_del_servicio__text = mysqli_escape_string($conn, $data['AD_nombre_del_servicio__text']);
        $AD_numero_de_telefono_del_bot__tel = mysqli_escape_string($conn, $data['AD_numero_de_telefono_del_bot__tel']);
        $AD_numero_de_telefono_con_permiso_de_administrador__text = mysqli_escape_string($conn, $data['AD_numero_de_telefono_con_permiso_de_administrador__text']);
        $AD_prefijo_internacional__number = mysqli_escape_string($conn, $data['AD_prefijo_internacional__number']);

        if(count(ARRAY_ADMIN_COLUMNS) !== 0){
            $consulta = "";
            foreach (ARRAY_ADMIN_COLUMNS as $column){
                $columnName = $column['name'];
                $value = mysqli_escape_string($conn, $data[$columnName]);
                $consulta .= $columnName ." = '". $value ."',";
            }
            $consulta = rtrim($consulta, ',');
            $consulta = ",".$consulta;
        }
        
        $sql = "UPDATE admin_bot SET
            AD_email_ = '$AD_email_',
            AD_nombre_del_servicio__text = '$AD_nombre_del_servicio__text',
            AD_numero_de_telefono_del_bot__tel = '$AD_numero_de_telefono_del_bot__tel',
            AD_numero_de_telefono_con_permiso_de_administrador__text = '$AD_numero_de_telefono_con_permiso_de_administrador__text',
            AD_prefijo_internacional__number = '$AD_prefijo_internacional__number'
            $consulta
        WHERE AD_id_ = '$AD_id_'";
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error al actualizar el admin: " . $sql . "<br>" . mysqli_error($conn).'<br>';
        }
    }

    //Obtener fila por el id del admin
    static public function getAdminById($id){
        $conn = ServiceDb::connect();

        $sql = "SELECT * FROM admin_bot WHERE AD_id_ = '$id'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }else {
            $row = false;
        }

        return $row;
    }



    //obtener todos los nombres de servicios de un telefono de admin
    static public function getNameService($phone){
        $conn = ServiceDb::connect();

        $sql = "SELECT
                AD_tipo_servicio_,
                AD_nombre_del_servicio__text
                FROM admin_bot WHERE AD_numero_de_telefono_del_bot__tel = '$phone'";
        $result = mysqli_query($conn, $sql);

        $services = array();
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $services[] = $row['AD_tipo_servicio_'];
            }
        }else {
            $services = false;
        }
        
        return $services;
    }

    //comprobar que el usuario es un admin
    static public function isAdmin($adminPhone, $userPhone, $service){
        //obtener AD_numero_de_telefono_con_permiso_de_administrador__text de un admin con admin phone y service
        $conn = ServiceDb::connect();

        $sql = "SELECT 
                AD_numero_de_telefono_con_permiso_de_administrador__text,
                AD_nombre_del_servicio__text
                FROM admin_bot WHERE AD_numero_de_telefono_del_bot__tel = '$adminPhone' AND AD_nombre_del_servicio__text = '$service' AND AD_numero_de_telefono_con_permiso_de_administrador__text LIKE '%$userPhone%'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

    //obtener el mail a partir del número de telefono
    static public function getMail($phone){
        $conn = ServiceDb::connect();

        $sql = "SELECT AD_email_ FROM admin_bot WHERE AD_numero_de_telefono_del_bot__tel = '$phone'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $mail = $row['AD_email_'];
        }else {
            $mail = 'No existe ningún administrador con ese número de teléfono';
        }

        return $mail;
    }
}

 
