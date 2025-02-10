<?php

include_once 'lang/es.php';

class TiposDeServicio{
    static public function initTiposDeServicio(){
        $servicios = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            $servicios[] = [
                "id_servicio" => $servicio['id_servicio'],
                "name_servicio" => $servicio['name_servicio'],
                "text_servicio" => $servicio['text_servicio']
            ];
        }

        if(empty($servicios)){ $servicios = false; }

        if($servicios !== false){
            echo json_encode(['status' => '200', 'message' => 'Servicios disponibles', 'data' => $servicios]);
        }else{
            echo json_encode(['status' => '400', 'message' => 'No hay servicios']);
        }
    }
}