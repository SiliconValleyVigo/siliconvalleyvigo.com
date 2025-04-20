<?php

class Sesion {
    static public function getSesionPosition($userId, $adminPhone, $userPhone){
        $sesionData = SesionDb::createReadSesion($userId, $adminPhone);
        $servicioId = $sesionData['SE_servicio_id'];
        $estadoId = $sesionData['SE_estado_id'];
        $opcionId = $sesionData['SE_opcion_id'];
        $campoId = $sesionData['SE_campo_id'];
        $json = $sesionData['SE_json'];

        $serviciosAdmin = AdminDb::getNameService($adminPhone);

        if($servicioId === null || $servicioId === false || $servicioId === "" || $servicioId === 0){
            $data = self::getServicios($serviciosAdmin, $userPhone);
            $position = false;
            $sesion = [
                'posicion' => $position,
                'ids'      => self::getIdsArray($data),
                'datos'    => $data
            ];   
        }else{
            $data = self::getEstados($servicioId);
            $position = 'servicio';
            $sesion = [
                'posicion' => $position,
                'ids'      => self::getIdsArray($data),
                'datos'    => $data
            ];
        }

        if($estadoId !== NULL && $estadoId !== false && $estadoId !== "" && $estadoId !== 0){
            $data = self::getOpciones($servicioId, $estadoId);
            $position = 'estado';
            $sesion = [
                'posicion' => $position,
                'ids'      => self::getIdsArray($data),
                'datos'    => $data
            ];
        }

        if($opcionId !== null && $opcionId !== false && $opcionId !== "" && $opcionId !== 0){
            $opcion = self::getOpcion($servicioId, $estadoId, $opcionId);
            if($campoId !== null && $campoId !== false && $campoId !== "" && $campoId !== 0){
                $campo = self::getCampo($servicioId, $estadoId, $opcionId, $campoId);
                $position = 'formulario';

                $sesion = [
                    'posicion' => $position,
                    'json'     => $json,
                    'campo_id' => $campoId,
                    'campo'    => $campo,
                    'ids'      => self::getIdsCampos($servicioId, $estadoId, $opcionId),
                    'datos'    => $opcion
                ];
            }else{
                $position = 'opcion';
                $sesion = [
                    'posicion' => $position,
                    'json'   => $json,
                    'campo_id'  => $campoId,
                    'ids'      => [],
                    'datos'    => $opcion
                ];
            }
        }
        return $sesion;
    }

    static public function getServicios($serviciosAdmin, $userPhone){
        $servicios = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            if(in_array($servicio['id_servicio'], $serviciosAdmin) ){
                $servicios[] = [
                    'id'         => $servicio['id_servicio'],
                    'lista'      => $servicio['id_servicio'].".- ".$servicio['name_servicio'],
                    'check_user' => $servicio['check_user'],
                    'check_admin'=> $servicio['check_admin']
                ];
            }
        }
        
        $serviciosDisponibles = User::checkUserServices($servicios, $userPhone);
        
        return $serviciosDisponibles;
    }

    static public function getEstados($servicioId){
        $arrayEstados = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            if($servicio['id_servicio'] == $servicioId){
                $arrayEstados = $servicio['array_estados'];
            }
        }

        $estados = [];
        foreach($arrayEstados as $estado){
            $estados[] = [
                'id'         => $estado['id_estado'],
                'lista'      => $estado['id_estado']." - ".$estado['name_estado']
            ];
        }

        return $estados;
    }

    static public function getOpciones($servicioId, $estadoId){
        $arrayEstados = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            if($servicio['id_servicio'] == $servicioId){
                $arrayEstados = $servicio['array_estados'];
            }
        }
        
        $arrayOpciones = [];
        foreach($arrayEstados as $estado){
            if($estado['id_estado'] == $estadoId){
                $arrayOpciones = $estado['array_opciones'];
            }
        }

        $opciones = [];
        foreach($arrayOpciones as $opcion){
            $opciones[] = [
                'id'    => $opcion['id_opcion'],
                'lista' => $opcion['id_opcion']." - ".$opcion['name_opcion']
            ];
        }

        return $opciones;
    }

    static public function getOpcion($servicioId, $estadoId,  $opcionId){
        $arrayEstados = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            if($servicio['id_servicio'] == $servicioId){
                $arrayEstados = $servicio['array_estados'];
            }
        }

        $arrayOpciones = [];
        foreach($arrayEstados as $estado){
            if($estado['id_estado'] == $estadoId){
                $arrayOpciones = $estado['array_opciones'];
            }
        }

        $formulario = [];
        foreach($arrayOpciones as $opcion){
            if($opcion['id_opcion'] == $opcionId){
                $formulario = [
                    "id_opcion"            => $opcion['id_opcion'],
                    "name_opcion"          => $opcion['name_opcion'],
                    "consulta"             => $opcion['consulta'],
                    "formartear_respuesta" => $opcion['formartear_respuesta'],
                    "consulta_alt"         => $opcion['consulta_alt'],
                    "consulta_alt_cont"    => $opcion['consulta_alt_cont'],
                    "respuesta_ok"         => $opcion['respuesta_ok'],
                    "respuesta_vacia"      => $opcion['respuesta_vacia'],
                    "respuesta_error"      => $opcion['respuesta_error'],
                    "respuesta_error_alt"  => $opcion['respuesta_error_alt'],
                    "formulario"           => $opcion['formulario']
                ];
            }
        }
        return $formulario;
    }

    static public function getCampos($servicioId, $estadoId, $opcionId){
        $arrayEstados = [];
        foreach(ARRAY_SERVICIOS as $servicio){
            if($servicio['id_servicio'] == $servicioId){
                $arrayEstados = $servicio['array_estados'];
            }
        }

        $arrayOpciones = [];
        foreach($arrayEstados as $estado){
            if($estado['id_estado'] == $estadoId){
                $arrayOpciones = $estado['array_opciones'];
            }
        }

        $arrayCampos = [];
        foreach($arrayOpciones as $opcion){
            if($opcion['id_opcion'] == $opcionId){
                $arrayCampos = $opcion['formulario'];
            }
        }
        
        $campos = [];
        if($arrayCampos !== false){
            foreach($arrayCampos as $campo){
                $campos[] = [
                    "id_campo"        => $campo['id_campo'],
                    "resumen"         => $campo['resumen'],
                    "campo"           => $campo['campo'],
                    "key"             => $campo['key'],
                    "respuesta_error" => $campo['respuesta_error'],
                    "check_send"      => $campo['check_send'],
                    "check_contiene"  => $campo['check_contiene']
                ];
            }
        }else{
            $campos = false;
        }

        return $campos;
    }

    static public function getCampo($servicioId, $estadoId, $opcionId, $campoId){
        $arrayCampos = self::getCampos($servicioId, $estadoId, $opcionId);

        $campo = [];
        if($arrayCampos !== false){
            foreach ($arrayCampos as $Arraycampo){
                if($Arraycampo['id_campo'] == $campoId){
                    $campo = [
                        "id_campo"        => $Arraycampo['id_campo'],
                        "resumen"         => $Arraycampo['resumen'],
                        "campo"           => $Arraycampo['campo'],
                        "key"             => $Arraycampo['key'],
                        "respuesta_error" => $Arraycampo['respuesta_error'],
                        "check_send"      => $Arraycampo['check_send'],
                        "check_contiene"  => $Arraycampo['check_contiene']
                    ];
                }
            }
        }else{
            $campo = false;
        }
        
        return $campo;
    }

    static public function getIdsCampos($servicioId, $estadoId, $opcionId){
        $campos = self::getCampos($servicioId, $estadoId, $opcionId);

        $idsCampos = [];
        foreach($campos as $campo){
            $idsCampos[] = $campo['id_campo'];
        }

        return $idsCampos;
    }

    static public function getIdsArray($ids){
        $idsArray = [];
        foreach($ids as $id){
            $idsArray[] = $id['id'];
        }

        return $idsArray;
    }

}