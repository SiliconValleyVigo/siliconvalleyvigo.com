<?php

include_once ('DB/adminDB.php');

class Singup{
    static public function initSingup($requests){
        $mail = $requests['AD_email_'];
        $password = $requests['AD_password_'];
        $servicio = $requests['AD_tipo_servicio_'];
        $nombreServicio = $requests['AD_nombre_del_servicio__text'];
        $adminPhone =$requests['AD_numero_de_telefono_del_bot__tel'];
        $password = Singup::encryptPassword($password);
        
        if($mail != '' && $mail != null && $password != '' && $password != null){
            $register = AdminDb::postUser($mail, $password, $servicio, $adminPhone, $nombreServicio);

            if($register !== 'existe' && $register === true ){
                //obtener datos del usuario registrado
                echo json_encode(['status' => '200', 'message' => 'Usuario registrado correctamente']);
            } elseif($register != 'existe') {
                echo json_encode(['status' => '400', 'message' => 'Error al registrar usuario']);
            }
        }else{
            echo "Los datos están incompletos";
        }
    }

    //encriptar password segun el algorithmo de sha256
    static public function encryptPassword($password){
        $password = hash('sha256', $password);
        return $password;
    }
}