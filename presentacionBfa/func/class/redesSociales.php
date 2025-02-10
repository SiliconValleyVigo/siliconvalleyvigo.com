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
    static public function presentacion($sesion, $data){
        $n = $data['text'];

        switch ($n) {
            case '1':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/1_que_es_wairbot.pdf",
                            "name": "1_que_es_wairbot.pdf",
                            "type": "pdf",
                            "text": "Qué soy"
                        }]';
                break;
            
            case '2':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/2_ventajas_wairbot.pdf",
                            "name": "2_ventajas_wairbot.pdf",
                            "type": "pdf",
                            "text": "Mis ventajas"
                        }]';
                break;

            case '3':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/3_diferencias_wairbot.pdf",
                            "name": "3_diferencias_wairbot.pdf",
                            "type": "pdf",
                            "text": "En que me diferencio"
                        }]';
                break;

            case '4':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/4_viabilidad.pdf",
                            "name": "4_viabilidad.pdf",
                            "type": "pdf",
                            "text": "Soy viable y escalable"
                        }]';
                break;

            case '5':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/5_plan_complementario.pdf",
                            "name": "5_plan_complementario.pdf",
                            "type": "pdf",
                            "text": "El plan para mi complemento"
                        }]';
                break;

            case '6':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/6_razones_presentacion_BFA.pdf",
                            "name": "6_razones_presentacion_BFA.pdf",
                            "type": "pdf",
                            "text": "Razones por las que mis desarrolladores se presentan a BFA"
                        }]';
                break;
            
            default:
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/7_presentacion_completa.pdf",
                            "name": "7_presentacion_completa.pdf",
                            "type": "pdf",
                            "text": "Presentación completa"
                        }]';
                break;
        }

        return $respuesta;
    }


    static public function presentacionAux($n){

        switch ($n) {
            case '1':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/1_que_es_wairbot.pdf",
                            "name": "1_que_es_wairbot.pdf",
                            "type": "pdf",
                            "text": "Qué soy"
                        }]';
                break;
            
            case '2':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/2_ventajas_wairbot.pdf",
                            "name": "2_ventajas_wairbot.pdf",
                            "type": "pdf",
                            "text": "Mis ventajas"
                        }]';
                break;

            case '3':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/3_diferencias_wairbot.pdf",
                            "name": "3_diferencias_wairbot.pdf",
                            "type": "pdf",
                            "text": "En que me diferencio"
                        }]';
                break;

            case '4':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/4_viabilidad.pdf",
                            "name": "4_viabilidad.pdf",
                            "type": "pdf",
                            "text": "Soy viable y escalable"
                        }]';
                break;

            case '5':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/5_plan_complementario.pdf",
                            "name": "5_plan_complementario.pdf",
                            "type": "pdf",
                            "text": "El plan para mi complemento"
                        }]';
                break;

            case '6':
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/6_razones_presentacion_BFA.pdf",
                            "name": "6_razones_presentacion_BFA.pdf",
                            "type": "pdf",
                            "text": "Razones por las que mis desarrolladores se presentan a BFA"
                        }]';
                break;
            
            default:
                $respuesta = '[{
                            "url": "https://siliconvalleyvigo.com/presentacionBfa/7_presentacion_completa.pdf",
                            "name": "7_presentacion_completa.pdf",
                            "type": "pdf",
                            "text": "Presentación completa"
                        }]';
                break;
        }

        return $respuesta;
    }

    static public function formateado($consulta, $sesion, $data){

    }
}
