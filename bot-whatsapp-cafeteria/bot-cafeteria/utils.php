<?php

class Utils {
    static public function ArrayEnMinusculas($array){
        if (is_array($array)) {
            $array_min = array();
            foreach ($array as $key => $value) {
                $array_min[$key] = strtolower($value);
            }
            return $array_min;
        }else{
            return 'Error en la función ArrayEnMinusculas';
        }
    }

    //Sustituye los codigos unicode por sus caracteres correspondientes
    static public function unicodeConvert($response){
        $response = utf8_encode($response);
        $normalArray= array('á','Á','é','É','í','Í','ó','Ó','ú','Ú','ñ','Ñ','°','');
        $unicodeArray= array('\u00e1','\u00c1','\u00e9','\u00c9','\u00ed','\u00cd','\u00f3','\u00d3','\u00fa','\u00da','\u00f1','\u00d1','\u00b0','\\');
        $response = str_replace($unicodeArray, $normalArray, json_encode($response));
        return $response;
    }

    //Comprobar que un texto sea base64
    static public function checkBase64($cadena){
        $cadena = str_replace('/', '', $cadena);

        if (substr($cadena, 0, 2) == '9j' || substr($cadena, 0, 2) == 'iV'){
            $esBase64 = true;
        }else{
            $esBase64 = false;
        }

        return $esBase64;
    }

    //comprobar si una imagen es jpg o png
    static public function tipeImage($imgBase64){
        $esBase64 = self::checkBase64($imgBase64);
        if($esBase64 === true){
            if(substr($esBase64, 0, 2) == '9j'){
                $tipo = "jpg";
            }elseif(substr($esBase64, 0, 2) == 'iV'){
                $tipo = "png";
            }else{
                $tipo = false;
            }
        }else{
            $tipo = false;
        }
        return $tipo;
    }

    static public function limpiarString($cadena){
        $isImage = Utils::checkBase64($cadena);

        if($isImage === true) {
            return $cadena;
        }
        //si cadena contiene fecha
        if (strpos($cadena, 'fecha') !== false) {
           return $cadena;
        }

        if (strpos($cadena, 'Referencia') !== false) {
            $cadena = str_replace('Referencia', 'referencia', $cadena);
        }

        if (strpos($cadena, 'REFERENCIA') !== false) {
            $cadena = str_replace('REFERENCIA', 'referencia', $cadena);
        }

        if (strpos($cadena, 'referencia') !== false) {
            return $cadena;
        }

        if (strpos($cadena, 'fecha') === false &&
            strpos($cadena, 'referencia') === false &&
            $isImage === false) {
                
            //Codificamos la cadena en formato utf8 en caso de que nos de errores
            //$cadena = utf8_encode($cadena);
            
            //Ahora reemplazamos las letras
            $cadena = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $cadena
            );
        
            $cadena = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $cadena );
            
            $cadena = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $cadena );
            
            $cadena = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $cadena );

            $cadena = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $cadena );
            
            $cadena = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C'),
                $cadena
            );

            $cadena = strtolower($cadena);

            $cadena = str_replace(' ', 'jjkzzzkhh', $cadena);
            $cadena = str_replace(',', 'jjkvvvkhh', $cadena);
            $cadena = str_replace("'", 'jjkyyykhh', $cadena);
            $cadena = str_replace('.', 'jjkwwwkhh', $cadena);
        
            $cadena = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $cadena);

            $cadena = str_replace('jjkzzzkhh', ' ', $cadena);
            $cadena = str_replace('jjkvvvkhh', ',', $cadena);
            $cadena = str_replace('jjkyyykhh', "'", $cadena);
            $cadena = str_replace('jjkwwwkhh', '.', $cadena);

            return $cadena;
        }
    }

    static public function dateFormat($fecha){
        $fecha = strtolower($fecha);

        $fecha = str_replace('enero', '01', $fecha);
        $fecha = str_replace('febrero', '02', $fecha);
        $fecha = str_replace('marzo', '03', $fecha);
        $fecha = str_replace('abril', '04', $fecha);
        $fecha = str_replace('mayo', '05', $fecha);
        $fecha = str_replace('junio', '06', $fecha);
        $fecha = str_replace('julio', '07', $fecha);
        $fecha = str_replace('agosto', '08', $fecha);
        $fecha = str_replace('septiembre', '09', $fecha);
        $fecha = str_replace('octubre', '10', $fecha);
        $fecha = str_replace('noviembre', '11', $fecha);
        $fecha = str_replace('diciembre', '12', $fecha);

        //eliminar caracteres de letras de la fecha
        $arrayLetras = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z'];
        foreach ($arrayLetras as $letra) {
            $fecha = str_replace($letra, '', $fecha);
        }

        $fecha = str_replace('/', '-', $fecha);
        $fecha = str_replace(' ', '-', $fecha);
        $fecha = str_replace('.', '-', $fecha);
        $fecha = str_replace('de', '-', $fecha);
        $fecha = str_replace('d', '-', $fecha);
        $fecha = str_replace('el', '', $fecha);
        $fecha = str_replace('l', '', $fecha);
        $fecha = str_replace('---', '-', $fecha);
        $fecha = str_replace('--', '-', $fecha);

        $fecha = str_replace('-1-', '-01-', $fecha);
        $fecha = str_replace('-2-', '-02-', $fecha);
        $fecha = str_replace('-3-', '-03-', $fecha);
        $fecha = str_replace('-4-', '-04-', $fecha);
        $fecha = str_replace('-5-', '-05-', $fecha);
        $fecha = str_replace('-6-', '-06-', $fecha);
        $fecha = str_replace('-7-', '-07-', $fecha);
        $fecha = str_replace('-8-', '-08-', $fecha);
        $fecha = str_replace('-9-', '-09-', $fecha);

        $fecha = str_replace('-1-', '-01-', $fecha);
        $fecha = str_replace('-2-', '-02-', $fecha);
        $fecha = str_replace('-3-', '-03-', $fecha);
        $fecha = str_replace('-4-', '-04-', $fecha);
        $fecha = str_replace('-5-', '-05-', $fecha);
        $fecha = str_replace('-6-', '-06-', $fecha);
        $fecha = str_replace('-7-', '-07-', $fecha);
        $fecha = str_replace('-8-', '-08-', $fecha);
        $fecha = str_replace('-9-', '-09-', $fecha);

        if (substr($fecha, 0, 1) == '-'){
            $fecha = substr($fecha, 1);
        }

        $fecha = DateTime::createFromFormat('d-m-Y', $fecha);
        $fecha = $fecha->format('Y-m-d');

        if (substr($fecha, 0, 1) == '0'){
            $fecha = substr($fecha, 1);
            $fecha = '2'.$fecha;
        }

        return $fecha;
    }

    /**
     * Parsea una fecha y la formatea
     *
     * @param string $fecha Fecha a formatear
     * @param bool $retunrArray True si debe devolver un array, false para devolver string
     * @param string $separatedBy Caracter de separación en caso de devolver un string
     * @param bool $inputAnglo True si la fecha de entrada es en formato anglosajón AAAA/MM/DD
     * @param bool $returnAnglo True para que devuelva la fecha en formato anglosajón AAAA/MM/DD
     * @param bool $haveYear True si se incluye el año en la fecha a formatear
     * @param array $monthsText Meses del año
     * @param array $monthsNombers Meses en formato numérico
     * @return array|string|false Fecha formateada
     */
    static public function formatearFeha(
        string $fecha,
        bool   $returnArray   = false,
        string $separatedBy   = '/',
        bool   $inputAnglo    = false,
        bool   $returnAnglo   = false,
        bool   $haveYear      = true,
        array  $monthsText    = [_enero, _febrero, _marzo, _abril, _mayo, _junio, _julio, _agosto, _septiembre, _octubre, _noviembre, _diciembre],
        array  $monthsNumbers = ['01','02','03','04','05','06','07','08','09','10','11','12']
    ){
        $fecha = strtolower($fecha);
        $fecha = str_replace($monthsText, $monthsNumbers, $fecha);

        //eliminar caracteres de letras de la fecha
        $arrayLetras = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','x','y','z'];
        foreach ($arrayLetras as $letra) {
            $fecha = str_replace($letra, '', $fecha);
        }

        $fecha = str_replace('/', '-', $fecha);
        $fecha = str_replace(' ', '-', $fecha);
        $fecha = str_replace('.', '-', $fecha);
        $fecha = str_replace('---', '-', $fecha);
        $fecha = str_replace('--', '-', $fecha);
        
        $fecha = explode("-", $fecha);
        $fecha = array_filter($fecha, 'strlen');
        
        if(count($fecha) < 1){
            $fecha = false;
        }else{
            $d = "";
            $m = "";
            $y = "";
    
            if(count($fecha) === 3){
                if($inputAnglo === false){
                    $d = $fecha[0];
                    $m = $fecha[1];
                    $y = $fecha[2];
                }else{
                    $d = $fecha[2];
                    $m = $fecha[1];
                    $y = $fecha[0];
                }
            }

            if(count($fecha) === 2){
                if($inputAnglo === false){
                    $m = $fecha[0];
                    $y = $fecha[1];
                }else{
                    $m = $fecha[1];
                    $y = $fecha[0];
                }
            }

            if(count($fecha) === 1){
                $y = $fecha[0];
            }

            if($d !== ""){
                if(strlen($d) !== 2){
                    if(strlen($d) < 2){
                        $d = "0".$d;
                    }
                    if(strlen($d) > 2){
                        $d = substr($d, -2);
                    }
                }
            }

            if($m !== ""){
                if(strlen($m) !== 2){
                    if(strlen($m) < 2){
                        $m = "0".$m;
                    }
                    if(strlen($d) > 2){
                        $m = substr($m, -2);
                    }
                }
            }
           
            if($y !== ""){
                if(strlen($y) !== 4){
                    if(strlen($y) === 1){
                        $y = "200".$y;
                    }
                    if(strlen($y) === 2){
                        $y = "20".$y;
                    }
                    if(strlen($y) === 3){
                        $y = "2".$y;
                    }
                    if(strlen($d) > 4){
                        $y = substr($y, -4);
                    }
                }
            }else{
                if($haveYear === true){
                    $fecha = false;
                }
            }

            if($fecha !== false){
                $fechaArray = [
                    "d" => $d,
                    "m" => $m,
                    "y" => $y
                ];
    
                if($returnArray === false){
                    if($returnAnglo === false){
                        if($fechaArray['d'] !== ""){
                            $fecha = $fechaArray['d'].$separatedBy.$fechaArray['m'].$separatedBy.$fechaArray['y'];
                        }else{
                            if($fechaArray['m'] !== ""){
                                $fecha = $fechaArray['m'].$separatedBy.$fechaArray['y'];
                            }else{
                                $fecha = $fechaArray['y'];
                            }
                        }
                    }else{
                        if($fechaArray['d'] !== ""){
                            $fecha = $fechaArray['y'].$separatedBy.$fechaArray['m'].$separatedBy.$fechaArray['d'];
                        }else{
                            if($fechaArray['m'] !== ""){
                                $fecha = $fechaArray['y'].$separatedBy.$fechaArray['m'];
                            }else{
                                $fecha = $fechaArray['y'];
                            }
                        }
                    }
                }else{
                    $fecha = $fechaArray;
                }
            }
        }
        
        return $fecha;
    }

    static public function esFecha($array, $sesion, $data){
        $fecha = $data['text'];
        $esFecha = self::formatearFeha($fecha);

        if ($esFecha !== false){
            $esFecha = true;
        }

        return $esFecha;
    }

    /**
    * Comprobar si una palabra es similar a otra, si es similar devuelve la palabra buena
    * @param string $palabraBuenaOriginal es la plabra que se desea comprobar si es similar.
    * @param string $palabraConsultaOriginal es el imput original a comprobar
    * @return false|string
    */
    static public function similar($palabraBuenaOriginal, $palabraConsultaOriginal){
        $palabraBuena = strtolower($palabraBuenaOriginal);
        $palabraConsulta = strtolower($palabraConsultaOriginal);

        $letrasBuenas = str_split($palabraBuena);
        $letrasConsulta = str_split($palabraConsulta);

        $letrasExisten = [];
        $letrasNoExisten = [];
        foreach ($letrasBuenas as $letra){
            if(in_array($letra, $letrasConsulta)){
                $letrasExisten[] = $letra;
            }else{
                $letrasNoExisten[] = $letra;
            }
        }

        if (count($letrasExisten) >= (count($letrasBuenas) - 1)){ $correcto = true; }
        else{ $correcto = false; }

        if($correcto === true){
            if (count($letrasNoExisten) <= 1){ $correcto = true; }
            else{ $correcto = false; }
        }

        if($correcto === true){
            $parejasLetrasBuenas = str_split($palabraBuena, 2);
            $numeroParejasLetrasBuenas = count($parejasLetrasBuenas);
    
            if(($numeroParejasLetrasBuenas % 2) != 0){
                array_pop($parejasLetrasBuenas);
    
            }
    
            $parejasLetrasBuenasExisten = [];
            foreach ($parejasLetrasBuenas as $parejaLetras){
                if(strpos($palabraConsulta, $parejaLetras) !== false){
                    $parejasLetrasBuenasExisten[] = $parejaLetras;
                }
            }
    
            if(count($parejasLetrasBuenasExisten) >= (floor($numeroParejasLetrasBuenas/2))){
                $correcto = true;
            }else{
                $correcto = false;
            }
        }

        if($correcto === true){ $text = $palabraBuenaOriginal; }
        else{ $text = $palabraConsultaOriginal; }

        return $text;

    }


    /**
     * Enviar un email con archivo adjunto
     *
     * @param string $mailFrom : email desde el que se envía
     * @param string $mailTo : email de destino
     * @param string $mailReplyTo : email a donde responder
     * @param string $cuerpo : html dentro del body del mensaje
     * @param string $asunto : astunto del mail, titulo y cabecera del body
     * @param string $from : nombre del remitente
     * @param string $imagenBase64 : imagen en formato base64 sin: "data:image/*;base64"
     * @return void
     */
    static public function sendMail($mailFrom, $mailTo, $mailReplyTo, $cuerpo, $asunto, $from, $imagenBase64){

        $mailReplyTo = filter_var($mailReplyTo, FILTER_VALIDATE_EMAIL);

        $tipoDeImagen = self::tipeImage($imagenBase64);
        if($tipoDeImagen === false){
            $imagen = "";
        }else{
            $imagen = "<img src='data:image/$tipoDeImagen;base64,$imagenBase64' alt='Imagen' width='512px'/>";
        }

        $cuerpo = "
            <html>
                <head>
                   <meta charset='UTF-8' />
                   <title>$asunto</title>
                </head>
                <body>
                    <h1>$asunto</h1>
                    <div>$cuerpo</div>
                    <div>$imagen</div>
                    
                </body>
            </html>
        ";

        //para el envío en formato HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        //dirección del remitente
        $headers .= "From: $from <$mailFrom>\r\n";

        if($mailReplyTo !== false){
            $headers .= "Reply-To: $mailReplyTo" . "\r\n";
        }

        return mail($mailTo, $asunto, $cuerpo, $headers);
    }
 
    /**
     * Hacer una consulta a una API con un JSON
     *
     * @param string $url : URL del endpoint
     * @param array $data : array de datos que se envian como json
     * @return void
     */
    static public function file_post_contents(string $url, array $data){
        $opts = ['http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n" .
                             "Accept: application/json\r\n",
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($opts);
        $postData = file_get_contents($url, false, $context);
        
        return !empty($postData) ? json_decode($postData, true) : array();
    }

    static public function formatearHora($hora){
        $hora = preg_replace("/[^0-9]/", "", $hora);

        $nDigitos = strlen($hora);

        switch ($nDigitos){
            case 4:
                $hora = $hora;
                break;
            case 3:
                $hora = "0".$hora;
                break;
            case 2:
                $hora = $hora."00";
                break;
            case 1:
                $hora = "0".$hora."00";
                break;
            default:
                $hora = false;
                break;
        }
        if($hora !== false){
            $hora = substr($hora, 0, 2).":".substr($hora, 2, 2);
        }

        return $hora;
    }
}

