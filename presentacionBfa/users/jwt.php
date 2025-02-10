<?php
require_once "vendor/autoload.php";
require_once "confi.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Token {

    //Codificar data en jwt con valided de 1 mes
    static public function encodeToken($data){
        $time = time();

        $token = array(
            "iat" => $time,
            "exp" => $time + (60*60*24*30),
            "data" => $data
        );

        $jwt = JWT::encode($token, JWT_KEY, 'HS256');

        return $jwt;
    }

    //Comprobar que el token es valido
    static public function decodedToken($jwt){
        $decoded = JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));
            $checkTime = Token::checkTimeToken($decoded);
            
            if ($checkTime == true){
                return $decoded->data;
            }
        try {
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    //Comprobar que el token no ha expirado
    static public function checkTimeToken($decoded){
        $time = time();

        if($time > $decoded->exp){
            return false;
        }else{
            return true;
        }
    }

}