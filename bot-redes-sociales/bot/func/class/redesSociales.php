<?php
/*
const REDES_SOCIALES_KEY;
const DB_NAME;
const DB_USER;
const DB_PASS;
const DB_HOST;
*/

/*
consultas: $sesion, $data
    json: $sesion['json'];

    data: $data['text'];
          $data['userId'];
          $data['adminPhone'];
          $data['userPhone'];

datos de formateo: $consulta, $sesion, $data)
    consulta: es lo que envía desde las funciones de consulta
*/

class RedesSociales{
    //función para hacer un post en facebook e instagram con la url de la imagen
    static public function publicar($sesion, $data){
        $imagen = self::imagen($data['text']);
        
        $respuesta = true;
        //guardar en base de datos
        $row = self::guardarFila($imagen, $sesion['json'], $data['userPhone']);

        if($row === false){
            $respuesta = false;
        }

        //publicar en redes sociales
        if($respuesta){
            $redes = self::publicarRedesSociales($imagen, $sesion['json']);

            if($redes === false){
                $respuesta = false;
            }
        }

        if($respuesta === false){
            return "🤖! Disculpe, No hemos podido realizar la publicación por motivos técnicos, por favor, vuelva a intentarlo más tarde \n";
        }else{
            return "🤖! Su publicación se ha realizado correctamente \n\n Puedes seguir añadiendo productos \n";
        }
    }

    //publicar en redes sociales usando las apis-genericas
    static public function publicarRedesSociales($imagen, $json){
        $json = json_decode($json, true);

        $api = 'https://siliconvalleyvigo.com/apis-genericas/social-post/';

        $nombre = $json['nombre'];
        $descripcion = $json['descripcion'];
        $hastag = '#' . str_replace(' ', '', $json['tipo']);

        $text = $nombre . "\n" . $descripcion . "\n" . $hastag;


        // Datos que se van a enviar por POST
        $data = [
            'key' => REDES_SOCIALES_KEY,
            'idPageFacebook' => '111132061976018',
            'accessTokenFacebook' => 'EAAQy5K0n4wUBADrjZCEgdHNWAPlYa5ZCK2fse9Pquq48U2tDTvsfZCJZCBOW7NzEBHTHHcsrr0XXsOnWSQYjNcq1323WlVvvZAVGpluSz5Gb5xZBkQzpBOw7exdejorVTtYSZAOTNcxfQZCRXdiEWCBbSR0mFdrbDacQIBnzr905TxCZAfTTSnkIRZBHLJddOpJgoZD',
            'idPageInstagram' => '17841459317037192',
            'accessTokenInstagram' => 'EAAQy5K0n4wUBAF1nOnD3mQleQ7Kj5I2aysTavAl4fsJTDGOZBQu9wNJZCKfR5qfApLSb88Ei07xKYrBJDaVcamXVHglxLUF76SgXrzmIdmZCy6H5NcTZAdKtZCrXHQrPtlg12V4CEQRXZAdSQqpeREerxFZBjOwN1hbSpTZAXfB8PWSwESGMjU8q',
            'img' => $imagen,
            'text' => $text
        ];

        // Configuración de la petición
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($api, false, $context);
        $resultado = json_decode($response, true);
        
        return $resultado;
    }

    static public function guardarFila($imagen, $json, $telefono){
        $json = json_decode($json, true);
        $empresa = self::comprobarUsuario($telefono, true);
    
        $api = 'https://siliconvalleyvigo.com/apis-genericas/post-row/';
        $datos = [
            'tipo'        => $json['tipo'],
            'nombre'      => $json['nombre'],
            'descripcion' => $json['descripcion'],
            'empresa'     => $empresa,
            'imagen'      => $imagen
        ];
    
        $opciones = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'key' => REDES_SOCIALES_KEY,
                    'server' => DB_HOST,
                    'user' => DB_USER,
                    'pass' => DB_PASS,
                    'db' => DB_NAME,
                    'data' => json_encode($datos),
                    'table' => 'auto_tabla'
                ])
            ]
        ];
    
        $contexto = stream_context_create($opciones);
        $resultado = file_get_contents($api, false, $contexto);
    
        return $resultado;
    }
    

    //funcion para comprobar usuarios registrados
    static public function comprobarUsuario($telefono, $comercio = false){
        $telefonosPermitidos = [
            '34600857882' => 'Tienda Jesús',
            '34615693711' => 'Tienda Cesar',
        ];

        if (array_key_exists($telefono, $telefonosPermitidos)) {
            if (!$comercio) {
                $respuesta = true;
            } else {
                $respuesta = $telefonosPermitidos[$telefono];
            }
        } else {
            $respuesta = false;
        }

        return $respuesta;
    }

    //función para guardar una imagen en una carpeta y obtener la url
    static public function imagen($imagen){
        $tipo = Utils::tipeImage($imagen);

        // Guardamos la imagen en una carpeta con la api generica y obtenemos la url
        $api = "https://siliconvalleyvigo.com/apis-genericas/get-image-url/";
        $data = [
            "key"  => REDES_SOCIALES_KEY,
            "file" => $imagen,
            "type" => $tipo
        ];

        // Configuración de la petición
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($api, false, $context);

        return $response;
    }

    //comprobar si se ha enviado una imagen
    static public function checkImage($check_contiene, $sesion, $data){
        $checkImage = Utils::tipeImage($data['text']);

        if ($checkImage === false) {
            $respuesta = false;
        } else {
            $respuesta = true;
        }

        return $respuesta;
    }
}
