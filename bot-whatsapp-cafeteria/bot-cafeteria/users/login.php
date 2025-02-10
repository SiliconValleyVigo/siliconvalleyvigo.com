<?php
include_once ('DB/adminDB.php');
include_once ('users/jwt.php');

class Login{
    static public function initLogin($requests){
        $mail = $requests['AD_email_'];
        $password = Login::encryptPassword($requests['AD_password_']);

        $access = Login::getData($mail, $password);

        if($access !== false){
            echo json_encode(['status' => '200', 'message' => 'Usuario autenticado correctamente', 'data' => $access]);
        }else{
            echo json_encode(['status' => '400', 'message' => 'Usuario o password incorrectos']);
        }
    }

    //Obtener los datos del usuario si está registrado
    static public function getData($mail, $password){
        $data = AdminDb::checkUser($mail, $password);
        $token = Token::encodeToken($data);

        if($data !== false){
            $dataToken = [
                "token" => $token,
                "AD_id_" => $data['AD_id_'],
                "AD_email_" => $data['AD_email_'],
                "AD_validated_" => $data['AD_validated_'],
                "AD_tipo_servicio_" => $data['AD_tipo_servicio_'],
                "AD_nombre_del_servicio__text" => $data['AD_nombre_del_servicio__text'],
                "AD_numero_de_telefono_del_bot__tel" => $data['AD_numero_de_telefono_del_bot__tel'],
                "AD_numero_de_telefono_con_permiso_de_administrador__text" => $data['AD_numero_de_telefono_con_permiso_de_administrador__text'],
                "AD_prefijo_internacional__number" => $data['AD_prefijo_internacional__number']
            ];

            foreach (ARRAY_ADMIN_COLUMNS as $column){
                $columnName = $column['name'];
                $dataToken += [ $columnName => $data[$columnName] ];
            }

            return $dataToken;
        }else{
            return false;
        }
    }

    //encriptar password segun el algorithmo de sha256
    static public function encryptPassword($password){
        $password = hash('sha256', $password);
        return $password;
    }
}