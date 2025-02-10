<?php

class Conversacion {
    static public function proceso($userId, $requests){
        $adminPhone = $requests['adminPhone'];
        $userPhone = $requests['userPhone'];

        $posicionArray = Sesion::getSesionPosition($userId, $adminPhone, $userPhone);
        $posicion = $posicionArray['posicion'];
        $ids = $posicionArray['ids'];
        $datos = $posicionArray['datos'];

        $text = $requests['text'];

        if($text === 0 || $text ==="0"){
            $text = _volver;
        }

        switch ($posicion){
            case 'opcion':
                if($text === _volver){
                    $mensaje = self::mensajeOpciones($posicion, 'NULL', $userId, $adminPhone, $userPhone);
                }else{
                    $mensaje = self::mensajeOpciones($posicion, $text, $userId, $adminPhone, $userPhone);
                }
                break;
            case 'formulario':
                if($text === _volver){
                    $mensaje = self::mensajeOpciones($posicion, 'NULL', $userId, $adminPhone, $userPhone);
                }else{                    
                    $sesion = Sesion::getSesionPosition($userId, $adminPhone, $userPhone);
                    $data = [
                        'text' => $text,
                        'userId' => $userId,
                        'adminPhone' => $adminPhone,
                        'userPhone' => $userPhone
                    ];
                    $datosNuevos = [ 'SE_campo_id' => $sesion['campo_id'] ];
                    SesionDb::updateSesion($userId, $adminPhone, $datosNuevos);

                    $sesion = Sesion::getSesionPosition($userId, $adminPhone, $userPhone);

                    $mensaje = Respuesta::respuestaFormulario($sesion, $data);

                    //Obtener las keys del json DB para compararlas con la key del siguiente campo
                    $arrayJson = SesionDb::getElement($userId, $adminPhone, 'SE_json');
                    $arrayJson = json_decode($arrayJson['SE_json'], true);

                    if($arrayJson !== null){                        
                        //sumar +1 al campo_id en la db mientras la key existen en el json de la DB para saltar al siguiente campo.
                        if( intVal($sesion['campo_id']) < count($arrayJson)){
                            $toCheckCampoKey = $sesion['campo']['key'];
                            while(array_key_exists($toCheckCampoKey, $arrayJson)){
                                //Obtener el SE_campo_id de la DB
                                $campoIdDb = SesionDb::getElement($userId, $adminPhone, 'SE_campo_id');
                                $campoIdDb = strval($campoIdDb['SE_campo_id']+1);
        
                                SesionDb::updateElement($userId, $adminPhone, 'SE_campo_id', $campoIdDb);
                                $sesion = Sesion::getSesionPosition($userId, $adminPhone, $userPhone);
                                
                                $mensaje = $sesion["campo"]["campo"];
        
                                $toCheckCampoKey = $sesion['campo']['key'];
                            }
                        }else{
                            $mensaje = Respuesta::respuestaFormulario($sesion, $data);
                        }
                    }
                }
                break;
            default:
                $mensaje = self::seleccionarOpciones($text, $ids, $posicion, $userId, $adminPhone, $userPhone, $datos);
                break;
        }

        $n = $requests['text'];
        $imagenDesdeSeleccion = false;
        //comprobar que n es numerico
        if(is_numeric($n)){
            //convertir $n en un número
            $checkN = intval($n);
            //comprobar si n es un número mayor que 0 y menor que 7
            if($checkN > 0 && $checkN < 8){
                $mensaje = RedesSociales::presentacionAux($n);
                $imagenDesdeSeleccion = true;
            } 
        }
        

        if($imagenDesdeSeleccion === false){
            //En el caso de que se vallan a enviar una colección de archivos, se debe formatear según se especifica en la paid de whatsapp
            if(strpos($mensaje, '"url":') && strpos($mensaje, '"text":')){
                $mensajeOriginal = $mensaje;
                //eliminar lo que está antes del array
                $posicionArray = strpos($mensaje, '[');
                $mensaje = substr($mensaje, $posicionArray);
                //eliminar lo que está despues del array
                $mensaje = substr($mensaje, 0, strpos($mensaje, "]"));
                $opciones = str_replace($mensaje, "", $mensajeOriginal);
                $mensaje = $mensaje.'
                    ,{
                        "url": "",
                        "name": "",
                        "type": "",
                        "text":"'.str_replace(["]","\n"], ["","\\n"], $opciones).'"
                    }]';
            }  
        }else{
            if(strpos($mensaje, '"url":') && strpos($mensaje, '"text":')){
                $mensajeOriginal = $mensaje;
                //eliminar lo que está antes del array
                $posicionArray = strpos($mensaje, '[');
                $mensaje = substr($mensaje, $posicionArray);
                //eliminar lo que está despues del array
                $mensaje = substr($mensaje, 0, strpos($mensaje, "]"));
                $opciones = 'Escribe uno de estos numeros para saber más sobre mi: \n\n1.- ¿Qué soy? \n\n2.- Mis Ventajas \n\n3.- ¿En que me diferencio? \n\n4.- ¿Soy viable y escalable? \n\n5.- ¿Tengo algún plan complementario? \n\n6.- ¿Por qué mis desarrolladores se presentan a BFA? \n\n7.- Presentación Completa \n';
                $mensaje = $mensaje.'
                    ,{
                        "url": "",
                        "name": "",
                        "type": "",
                        "text":"'.$opciones.'"
                    }]';
            } 
        }
        

        return $mensaje;
    }

    static public function seleccionarOpciones($text, $ids, $posicion, $userId, $adminPhone, $userPhone, $datos){
        if(in_array($text, $ids)){
            $mensaje = self::mensajeOpciones($posicion, $text, $userId, $adminPhone, $userPhone);
        }elseif($text === _volver){
            $mensaje = self::mensajeOpciones($posicion, 'NULL', $userId, $adminPhone, $userPhone);
        }else{
            $mensaje = self::mensajeDefault($posicion, $datos);
        }

        return $mensaje;
    }

    static public function mensajeOpciones($posicion, $text, $userId, $adminPhone, $userPhone){
        $sesion = self::sesionStatus($posicion, $text, $userId, $adminPhone, $userPhone);
        $posicion = $sesion['posicion'];
        $datos = $sesion['datos'];
        if($posicion !== 'opcion' || $text === 'NULL'){
            $mensaje  = _elegir;
            $mensaje .= self::getListaOpciones($datos);
            $mensaje .= _opcion_volver;
        }else{
            $data = [
                'text' => $text,
                'userId' => $userId,
                'adminPhone' => $adminPhone,
                'userPhone' => $userPhone
            ];

            if($datos['formulario'] === false){
                $mensaje = Respuesta::respuestaOpcion($sesion, $data);
            }else{
                $campoId = $datos['formulario'][0]['id_campo'];
                $datosNuevos = [
                    'SE_campo_id' => $campoId
                ];
                SesionDb::updateSesion($userId, $adminPhone, $datosNuevos);
                $mensaje = Respuesta::respuestaFormulario($sesion, $data);
            }
        }

        return $mensaje;
    }

    static public function sesionStatus($posicion, $text, $userId, $adminPhone, $userPhone){
        if($text === 'NULL'){
            switch($posicion){
                case 'servicio': $key = 'SE_servicio_id'; break;
                case 'estado':   $key = 'SE_estado_id';   break;
                case 'opcion':   $key = 'SE_opcion_id';   break;
                default:  $key = false; break;
            }
        }else{
            switch($posicion){
                case 'servicio': $key = 'SE_estado_id';   break;
                case 'estado':   $key = 'SE_opcion_id';   break;
                case 'opcion':   $key = 'SE_opcion_id';   break;
                case false:      $key = 'SE_servicio_id'; break;
                default:  $key = false; break;
            }
        }

        if(($posicion === 'opcion' || $posicion === 'formulario') && $text === 'NULL'){
            $datosNuevos = [
                'SE_opcion_id' => 'NULL',
                'SE_campo_id'  => 'NULL',
                'SE_json'      => 'NULL'
            ];
        }else{
            $datosNuevos = [$key => $text];
        }

        SesionDb::updateSesion($userId, $adminPhone, $datosNuevos);
        return Sesion::getSesionPosition($userId, $adminPhone, $userPhone);
    }

    static public function mensajeDefault($posicion, $datos){
        $listaOpciones = self::getListaOpciones($datos);

        $mensaje = "";
        if($posicion === false){
            $mensaje .= _bienvenido;
            $mensaje .= _elegir;
            $mensaje .= $listaOpciones;
        }else{
            if(!in_array($posicion, ['servicio', 'estado', 'opcion'])){
                $mensaje .= _error_elegir;
                $mensaje .= $listaOpciones;
                $mensaje .= _opcion_volver;
            }
            if(is_array($posicion) === false){
                if($posicion === 'estado' || $posicion === 'opcion'){
                    $mensaje .= _error_elegir;
                    $mensaje .= $listaOpciones;
                    $mensaje .= _opcion_volver;
                }else{
                    $mensaje  = _bienvenido;
                    $mensaje .= _elegir;
                    $mensaje .= $listaOpciones;
                    $mensaje .= _opcion_volver;
                }
            }
        }

        return $mensaje;
    }

    static public function getListaOpciones($posicionArray){
        $listaOpciones = "";
        foreach($posicionArray as $posicion){
            $listaOpciones .= $posicion['lista']."\n";
        }
        return $listaOpciones;
    }


}
