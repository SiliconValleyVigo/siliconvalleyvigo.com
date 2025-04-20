<?php
include_once ('DB/adminDB.php');
include_once ('users/jwt.php');

class LoginMultiple{
    static public function initLogin($requests){
        $mail = $requests['AD_email_'];
        $password = self::encryptPassword($requests['AD_password_']);

        $access = self::getData($mail, $password);

        switch ($access){
            case "null":
                echo json_encode(['status' => '204', 'message' => 'Tienes servicios registrados pero aun no están disponibles']);
                break;

            case "false":
                echo json_encode(['status' => '400', 'message' => 'Usuario o password incorrectos']);
                break;

            default:
                echo json_encode(['status' => '200', 'message' => 'Usuario autenticado correctamente', 'data' => $access]);
                break;
        }
    }

    //Obtener los datos del usuario si está registrado
    static public function getData($mail, $password){
        $rows = AdminDb::getRowsUser($mail, $password);
        $token = Token::encodeToken($rows);

        if($rows !== false){
            //Obtener filas de servicios
            $servicios = [];
            foreach($rows as $data){

                //Columnas personalizadas
                $datosLogin = [];
                foreach (ARRAY_ADMIN_COLUMNS as $column){
                    $columnName = $column['name'];
                    $datosLogin[$columnName] = $data[$columnName];
                }

                //Columnas básicas
                $dataToken =[];
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

                //obtener los datos completos del servicio
                $servicios[] = array_merge($dataToken, $datosLogin);
            }

            //Mostrar solo los servicios validados
            $data = [];
            foreach($servicios as $servicio){
                $validado = $servicio['AD_validated_'];
                if($validado === 1 || $validado === "1"){
                    $data[] = $servicio;
                }
            }

            if(empty($data)){ $data = "null"; }

            return $data;
        }else{
            return "false";
        }
    }

    //encriptar password segun el algorithmo de sha256
    static public function encryptPassword($password){
        $password = hash('sha256', $password);
        return $password;
    }
}