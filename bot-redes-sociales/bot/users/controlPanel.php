<?php

include_once ('DB/adminDB.php');
include_once ('users/jwt.php');
include_once ('users/login.php');

class ControlPanel{
    static public function initControlPanel($requests){
        $jwt = $requests['token'];
        $id = $requests['AD_id_'];
        try{
            $data = Token::decodedToken($jwt);
        }catch(Exception $e){
            echo json_encode(['status' => '400', 'message' => 'Token no es valido']);
        }

        if (isset($data)){
            AdminDb::updateAdmin($requests);
            $data = AdminDB::getAdminById($id);

            //generar nuevo token con data
            $token = Token::encodeToken($data);
            $data['token'] = $token;

            unset($data['AD_password_']);
            unset($data['AD_validated_']);
            unset($data['timestamp']);

            if ($data != false){
                echo json_encode([
                    'status' => '200', 
                    'message' => 'Datos actualizados correctamente', 
                    'data' => $data
                ]);
            }
        }
    }
}