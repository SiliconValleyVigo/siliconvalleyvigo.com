<?php

class Documento {

    static public function porReferencia($sesion, $data){
        $consulta = self::consultaConfi($data['userPhone']);

        $datosFormulario = json_decode($sesion['json'], true);

        $referencia = $datosFormulario['ref'];

        $carpeta = $datosFormulario['carpeta'];
        if ($carpeta === 2){ $carpeta = 'albaranes';}
        else{$carpeta = 'facturas';}

        $consulta['json']['carpeta'] = $carpeta;
        $consulta['json']['referencia'] = $referencia;

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = $respuesta['data'];
        }

        return $respuesta;
    }

    static public function formatearPorReferencia($consulta, $sesion, $data){
        if ($consulta === false){
            $respuesta = _sinDocumentosConEsaReferencia;
        }else{
            $respuesta = json_encode($consulta);
        }

        return $respuesta;
    }

    static public function porFecha($sesion, $data ){
        $consulta = self::consultaConfi($data['userPhone']);

        $datosFormulario = json_decode($sesion['json'], true);

        $carpeta = $datosFormulario['carpeta'];
        if ($carpeta === 2){ $carpeta = 'albaranes';}
        else{$carpeta = 'facturas';}

        $consulta['json']['carpeta'] = $carpeta;

        //FORMATEAR LA FECHA 11/11/2022
        $fecha = $datosFormulario['fecha'];
        $fecha = Utils::formatearFeha($fecha, true);

        if($fecha['d'] === ""){$fecha['d'] = false;}
        if($fecha['m'] === ""){$fecha['m'] = false;}

        $consulta['json']['fecha_d'] = $fecha['d'];
        $consulta['json']['fecha_m'] = $fecha['m'];
        $consulta['json']['fecha_a'] = $fecha['y'];

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = $respuesta['data'];
        }

        return $respuesta;
    }

    static public function formatearPorFecha($consulta, $sesion, $data ){
        if ($consulta === false){
            $respuesta = _sinDocumentosDeEsaFecha;
        }else{
            $respuesta = json_encode($consulta);
        }

        return $respuesta;
    }

    static public function ultimoDocumento($sesion, $data ){
        $consulta = self::consultaConfi($data['userPhone']);

        $datosFormulario = json_decode($sesion['json'], true);

        $carpeta = $datosFormulario['carpeta'];
        if ($carpeta === 2){ $carpeta = 'albaranes';}
        else{$carpeta = 'facturas';}

        $consulta['json']['carpeta'] = $carpeta;
        $consulta['json']['ultima'] = true;

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = $respuesta['data'];
        }

        return $respuesta;
    }

    static public function formatearUltimoDocumento($consulta, $sesion, $data ){
        if ($consulta === false){
            $respuesta = _sinDocumentosConEsaReferencia;
        }else{
            $respuesta = json_encode($consulta);
        }

        return $respuesta;
    }

    static public function consultaConfi($userPhone){
        $json = [
            "key"        => API_KEY,
            "userPhone"  => $userPhone,
            "getCarpetas"=> false,
            "carpeta"    => false,
            "fecha_d"    => false,
            "fecha_m"    => false,
            "fecha_a"    => false,
            "referencia" => false,
            "ultima"     => false
        ];

        return [
            'url'  => 'https://rodapro.eu/bot-whatsapp-cafeteria/api-cafeteria/',
            'json' => $json
        ];
    }

}