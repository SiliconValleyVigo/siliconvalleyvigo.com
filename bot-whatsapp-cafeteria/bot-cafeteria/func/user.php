<?php

class User {
    static public function checkUserServices($arrayServicios, $userPhone){   
        $servicios = [];
        foreach($arrayServicios as $servicio){
            if($servicio['check_admin'] !== false){
                $isAdmin = call_user_func($servicio['check_admin'], $userPhone);
                if($isAdmin === true){
                    $servicios[] = $servicio; 
                }
            }
            if($servicio['check_user'] !== false){
                $isUser = call_user_func($servicio['check_user'], $userPhone);
                if($isUser === true){
                    $servicios[] = $servicio; 
                }
            }

            if($servicio['check_user'] === false && $servicio['check_admin'] === false){
                $servicios[] = $servicio; 
            }
        }

        return $servicios;
    }

    static public function checkUser($userPhone, $adminPhone){
        $serviciosAdmin = AdminDb::getNameService($adminPhone);
        $servicios = Sesion::getServicios($serviciosAdmin, $userPhone);
        $nServicios = count($servicios);

        if($nServicios === 0){
            $checkUser = false;
        }else{
            $checkUser = true;
        }

        return $checkUser;
    }

    static public function formatNumber($userPhone){
        if(substr($userPhone, 2) === "34"){
            $userPhone = substr($userPhone, 2);
        }

        return $userPhone;
    }

}
