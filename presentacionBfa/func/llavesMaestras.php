<?php

class LlavesMaestras {
    static public function initLlavesMaestras($data){
        $textOriginal = $data['text'];

        $esMaestra = false;
        foreach(ARRAY_LLAVES_MAESTRAS as $llave){

            //comprobar si contiene es true o false y si contiene es true comprobar si name contiene textOriginal
            if( $llave['contiene'] === true &&
                strpos(strtolower($textOriginal), strtolower($llave['name'])) !== false
            ){
                $text = $llave['name'];
            }else{
                $text = $textOriginal;
            }

            //Si similar es true, comprobar si textOriginal es similar a name
            if ( $text !== $llave['name'] &&
                 $llave['similar'] === true
                ){
                $text = Utils::similar($llave['name'], $text);
            }

            if($text !== $llave['name']){
                $text = false;
            }
            
            //obtener datos del
            if($text !== false){
                $esMaestra = true;
                $d =[
                    "name"      => $llave['name'], 
                    "respuesta" => $llave['respuesta'], 
                    "funcion"   => $llave['funcion'],
                    "similar"   => $llave['similar'], 
                    "contiene"  => $llave['contiene']
                ];
            }
        }
        
        if($esMaestra === true){
            if($d['funcion'] !== false){
                $respuesta = call_user_func($d['funcion'], $data);
            }else{
                $respuesta = $d['respuesta'];
            }
        }else{
            $respuesta = false;
        }

        return $respuesta;
    }



    ///////////////////////PRUEBAS//////////////////////////////////
    static public function pruebaLeia($data){
        echo '¡Preferiría besar a un Wookie!';
    }
}