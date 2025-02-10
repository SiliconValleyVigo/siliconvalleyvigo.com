<?php

class Cita {
    static public function reservarCita($sesion, $data){
        $consulta = self::consultaConfi($data['userPhone']);

        $datosFormulario = json_decode($sesion['json'], true);

        //Formatear Día
        $dia = $datosFormulario['dia'];
        $diaElegido =$dia;
        $dia = explode("/", $dia);

        $consulta['json']['fecha_d'] = $dia[0];
        $consulta['json']['fecha_m'] = $dia[1];
        $consulta['json']['fecha_a'] = $dia[2];

        //Formatear Hora
        $hora = Utils::formatearHora($datosFormulario['hora']);
        $horaElegida = $hora;
        $hora = str_replace(":", "", $hora);

        $consulta['json']['hora'] = $hora;

        //Consultar
        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = "Se ha registrado tu cita para el $diaElegido a las $horaElegida";
        }

        return $respuesta;
    }

    static public function formatearReservarCita($consulta, $sesion, $data){
        if ($consulta === false){
            $respuesta = "La fecha no se ha registrado";
        }else{
            $respuesta = $consulta;
        }

        return $respuesta;
    }

    static public function CheckExisteFechasHorasDisponibles($checkContiene, $sesion, $data){
        $checkCitaReservada = self::checkCitaExist($data['userPhone']);

        if($checkCitaReservada !== false){
            echo "Ya tienes una cita reservada: \n";
            echo $checkCitaReservada."\n";
            echo "No es posible reservar dos citas \n\n";
            echo "Escribe el nº 0 para volver al menú anterior y accede a Cancelar cita para poder solicitar una fecha distinta \n";
            $check = false;
        }else{
            $diasDisponibles = self::diasDisponibles($data['userPhone']);

            if ($diasDisponibles === false){
                echo "Lo sentimos, no tenemos días disponibles para reservar una cita, vuelva intentarlo más tarde. \n\n";
                echo "Escribe el nº 0 para volver al menú anterior";
                exit;
            }else{
                //Comprobar que es una fecha valida
                $diaText = Utils::formatearFeha($data['text']);

                if($diaText === false){
                    $check = false;
                }elseif(strlen($diaText) !== 10){
                    echo "La fecha debe contener el día, mes y año, por favor, vuelva a intentarlo.";
                    $check = false;
                }else{
                    //Comprobar que el día pasado por text existe en el array diasDisponibles
                    if(in_array($diaText, $diasDisponibles)){
                        //mostrar las horas disponibles para ese día
                        $horasDisponibles = self::getHorasDisponibles($data['userPhone'], $diaText);
                        $mensajeHorasDisponibles = "Estas son las horas disponibles para el $diaText: \n";
                        foreach($horasDisponibles as $hora){
                            $mensajeHorasDisponibles .= $hora."\n";
                        }
                        $salida = ob_get_contents();
                        ob_start();
                        if(strlen($salida) === 0){
                            echo $mensajeHorasDisponibles;
                        }
                        $check = true;
                    }else{
                        //Mostrar los días disponibles
                        $mensajeDiasDisponibles  = "El día indicado no está disponible para pedir cita \n\n";
                        $mensajeDiasDisponibles .= "Estos son los días disponibles: \n";
                        foreach($diasDisponibles as $dia){
                            $mensajeDiasDisponibles .= $dia."\n";
                        }
                        echo $mensajeDiasDisponibles;
                        $check = false;
                    }
                }
            }    
        }

        return $check;
    }

    static public function HorasDisponibles($checkContiene, $sesion, $data){
        $horaElegida = Utils::formatearHora($data['text']);

        if ($horaElegida === false){
            $check = false;
        }else{
            $datosFormulario = json_decode($sesion['json'], true);
            $dia = $datosFormulario['dia'];
            $horasDisponibles = self::getHorasDisponibles($data['userPhone'], $dia);

            if (in_array($horaElegida, $horasDisponibles)){
                $check = true;
            }else{
                $mensajeHorasDisponibles  = "Esa hora no está entre las disponibles\n";
                $mensajeHorasDisponibles .= "Escribe una de estas horas disponibles para el $dia: \n";
                foreach($horasDisponibles as $hora){
                    $mensajeHorasDisponibles .= $hora."\n";
                }
                echo $mensajeHorasDisponibles;
                $check = false;
            }
        }

        return $check;
    }

    static public function consultarCita($sesion, $data){
        $consulta = self::consultaConfi($data['userPhone']);

        $consulta['json']['comprobarCitas'] = true;

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = $respuesta['data'][0];
        }

        return $respuesta;
    }

    static public function formatearConsultarCita($consulta, $sesion, $data){
        if ($consulta === false){
            $respuesta = "No se han encontrado citas\n";
        }else{
            $respuesta = $consulta."\n";
        }

        return $respuesta;
    }

    static public function cancelarCita($sesion, $data){
        $consulta = self::consultaConfi($data['userPhone']);

        $consulta['json']['eliminarCita'] = true;

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = $respuesta['message'];
        }

        return $respuesta;
    }

    static public function formatearCancelarCita($consulta, $sesion, $data){
        if ($consulta === false){
            $respuesta = "No se han encontrado citas";
        }else{
            $respuesta = $consulta."\n";
        }

        return $respuesta;
    }

    static public function consultaConfi($userPhone){
        $json = [
            "key"             => API_KEY,
            "userPhone"       => $userPhone,
            "citaMasProxima"  => false,
            "diasDisponibles" => false,
            "comprobarCitas"  => false,
            "eliminarCita"    => false,
            "fecha_d"         => false,
            "fecha_m"         => false,
            "fecha_a"         => false,
            "hora"            => false,
        ];

        return [
            'url'  => 'https://rodapro.eu/bot-whatsapp-cafeteria/api-citas/',
            'json' => $json
        ];
    }

    static public function checkCitaExist($userPhone){
        $consulta = self::consultaConfi($userPhone);

        $consulta['json']['comprobarCitas'] = true;

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $respuesta = false;
        }else{
            $respuesta = $respuesta['data'][0];
        }

        return $respuesta;
    }

    static public function diasDisponibles($userPhone){
        $consulta = self::consultaConfi($userPhone);

        $consulta['json']['diasDisponibles'] = true;

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $arrayDias = false;
        }else{
            $arrayDias = $respuesta['data'];
        }

        return $arrayDias;
    }

    static public function getHorasDisponibles($userPhone, $dia){
        $consulta = self::consultaConfi($userPhone);

        $dia = explode("/", $dia);

        $consulta['json']['fecha_d'] = $dia[0];
        $consulta['json']['fecha_m'] = $dia[1];
        $consulta['json']['fecha_a'] = $dia[2];

        $respuesta = Utils::file_post_contents($consulta['url'], $consulta['json']);

        if (isset($respuesta['data']) === false){
            $arrayHoras = false;
        }else{
            $arrayHoras = $respuesta['data'];
        }

        return $arrayHoras;
    }
}