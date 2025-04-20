<?php

class Respuesta {
    static public function respuestaOpcion($sesion, $data) {
        $consluta = $sesion['datos']['consulta'];    
        $respuesta = call_user_func($consluta, $sesion, $data);

        $respuestaFormateada = $sesion['datos']['formartear_respuesta'];
        $mensaje = call_user_func($respuestaFormateada, $respuesta, $sesion, $data);

        $datosNuevos = [
            'SE_opcion_id' => 'NULL',
            'SE_campo_id'  => 'NULL',
            'SE_json'      => 'NULL'
        ];

        SesionDb::updateSesion($data['userId'], $data['adminPhone'], $datosNuevos);
        $sesion = Sesion::getSesionPosition($data['userId'], $data['adminPhone'], $data['userPhone']);
        $datos = $sesion['datos'];
        $mensaje .= "\n"._elegir;
        $mensaje .= Conversacion::getListaOpciones($datos);
        $mensaje .= _opcion_volver;

        return $mensaje;
    }

    static public function respuestaFormulario($sesion, $data){    
        $campoId = $sesion['campo_id'];

        if($campoId === NULL){
            $mensaje = _volver_en_cualquier_momento;
            $mensaje .= $sesion['datos']['formulario'][0]['campo'];
        }else{
            $campoActual   = $sesion['campo'];
            $check         = $campoActual['check_send'];
            $checkForArray = $campoActual['check_contiene'];

            if($check !== false ){
                $check = call_user_func($check, $checkForArray, $sesion, $data);
            }else{
                $check = true;
            }
            
            if($check === false){
                $d = $sesion['datos'];
                if($d['consulta_alt'] === false){
                    $mensaje  = $campoActual['respuesta_error'];
                    $mensaje .= $campoActual['campo'];
                }else{
                    $conslutaAlt  = call_user_func($d['consulta_alt'], $d['consulta_alt_cont'], $sesion, $data);
                    if($conslutaAlt === false){
                        $mensaje  = $d['respuesta_error_alt'];
                        $mensaje .= _opcion_volver;
                        $mensaje .= $campoActual['campo'];
                    }else{
                        $mensaje  = $conslutaAlt;
                        $mensaje .= _opcion_volver;
                        $mensaje .= $campoActual['campo'];
                    }
                }
            }else{
                $arrayJson = json_decode($sesion['json'], true);
                $ids       = $sesion['ids'];

                //Actualizar Json
                $jsonDB = SesionDb::getElement($data['userId'], $data['adminPhone'], 'SE_json');
                if($jsonDB['SE_json'] !== null){
                    $jsonDB = json_decode($jsonDB['SE_json'], true);
                    $arrayJson = $jsonDB;
    
                    $arrayJson[$campoActual['key']] = $data['text'];
                    $json = json_encode($arrayJson);
                }else{
                    $arrayJson = [$campoActual['key'] => $data['text']];
                    $json = json_encode($arrayJson);
                }               

                //Actualizar campoId
                $campoId = self::actualizarId($campoId, $ids);

                //Actualizar Sesion
                $sesion = self::actualizarSesion($json, $campoId, $data);
                $arrayJson = json_decode($sesion['json'], true);

                //Obtener Resumen
                $resumen = self::resumenFormulario($arrayJson, $sesion['campo']['resumen']);

                //Comprobar que el formulario esté completo
                $formularioCompleto = self::formularioCompleto($arrayJson, $sesion['ids']);
                
                //obtener respuesta final
                if($formularioCompleto === false){
                    $respuesta = "";
                    $pregunta = $sesion['campo']['campo'];
                }else{
                    $respuesta = self::consulta($sesion, $data);

                    //Actualizar user
                    $datosNuevos = [ 
                        'SE_json' => 'NULL',
                        'SE_campo_id'  =>$sesion['ids'][0]
                    ];
                    SesionDb::updateSesion($data['userId'], $data['adminPhone'], $datosNuevos);
                    
                    //Volver a comenzar el formulario
                    $pregunta = $sesion['datos']['formulario'][0]['campo'];
                }
                
                $mensaje  = $resumen;
                $mensaje .= $respuesta;
                $mensaje .= _opcion_volver;
                $mensaje .= $pregunta;
            }
        }

        return $mensaje;
    }

    static public function actualizarSesion($json, $campoId, $data){
        $datosNuevos = [ 
            'SE_json' => $json,
            'SE_campo_id'  =>$campoId
        ];
        SesionDb::updateSesion($data['userId'], $data['adminPhone'], $datosNuevos);
        return Sesion::getSesionPosition($data['userId'], $data['adminPhone'], $data['userPhone']);
    }

    static public function consulta($sesion, $data){
        $d = $sesion['datos'];
        $consulta = call_user_func($d['consulta'], $sesion, $data);
        
        switch ($consulta){
            case "false":
                $respuesta = $d['respuesta_error'];
                break;
            case "null":
                $respuesta = $d['respuesta_vacia'];
                break;
            default:
                if($d['formartear_respuesta'] === false){
                    $respuesta = $d['respuesta_ok'];
                }else{
                    var_dump('AQUI 3');
                    $respuesta = call_user_func($d['formartear_respuesta'], $consulta, $sesion, $data);
                    var_dump('AQUI 4');
                }
                break;
        }

        return $respuesta;
    }

    static public function formularioCompleto($arrayJson, $ids){
        $camposLlenos     = count($arrayJson);
        $camposParaLlenar = count($ids);

        if($camposLlenos === $camposParaLlenar){ $formularioCompleto = true ; }
        else{ $formularioCompleto = false ; }

        return $formularioCompleto;
    }

    static public function resumenFormulario($json, $resumen){
        if($resumen !== false){
            $mensaje = "";
            foreach($json as $key => $value){
                $mensaje .= $key . ": " . $value . "\n";
            }
        }else{ $mensaje = ""; }
        return $mensaje;
    }

    static public function actualizarId($campoId, $ids){
        $index = array_search($campoId, $ids);

        if(($index+1) >= count($ids)){
            $id = $ids[$index];
        }else{
            $id = $ids[$index+1];
        }
        return $id;        
    }

    ////////////////////////////////////////PRUEBAS////////////////////////////////////////
    static public function pruebaConsulta($sesion, $data){
        if($data['text'] == 11){
            $respuesta = "NULL";
        }elseif($data['text'] == 12){
            $respuesta = "false";
        }else{
            $respuesta = $data['userPhone'];
        }
       return $respuesta;
    }

    static public function pruebaFormatearRespuesta($respuesta, $sesion, $data){
        return "Esta es tu respuesta: $respuesta \n";
    }

    static public function pruebaCheck($contiene, $sesion, $data){
        $caracter = $data['text'];
        if(in_array($caracter, $contiene) && is_numeric($caracter)){
            $respuesta = "Es el Numero $caracter \n";
        }elseif(in_array($caracter, $contiene) && is_string($caracter)){
            $respuesta = "Es la letra $caracter \n";
        }else{
            $respuesta = false;
        }

        return $respuesta;
    }

}