<?php
include_once ('DB/adminDB.php');
include_once ('users/jwt.php');
include_once ('users/login.php');

class ChangePassword{
    static public function initChangePassword($requests){
        $id = $requests['AD_id_'];
        $mail = $requests['AD_email_'];
        $password = Login::encryptPassword($requests['AD_password_']);
        $passwordNew = Login::encryptPassword($requests['AD_password__change']);

        $checkPassword = AdminDB::checkPassword($mail, $password);

        if ($checkPassword !== false){
            AdminDB::updatePassword($mail, $passwordNew);
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
                    'message' => 'Contraseña actualizada correctamente', 
                    'data' => $data
                ]);
            }
        }else{
            echo json_encode(['status' => '400', 'message' => 'Contraseña incorrecta']);
        }
    }
}