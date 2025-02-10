<?php
const _usuarioNoRegistrado = "No estás registrado para ninguno de nuestros servicios";
const _volver = 'volver';
const _bienvenido = "🤖 Hola! soy Wairbot ¿Que te gustaría saber sobre mi? \n\n";
const _elegir = "🤖⚠️Hemos detectado una velocidad de transferencia de datos baja por causas ajenas a Wairbot\n\n🤖 Escribe uno de estos números para saber más sobre mi:\n\n";
const _error_elegir = "🤖⚠️Hemos detectado una velocidad de transferencia de datos baja por causas ajenas a Wairbot\n\n🤖 Escribe uno de estos números para saber más sobre mi: \n\n";
const _opcion_volver = "";
const _volver_en_cualquier_momento = "\n❌ Puedes VOLVER al menú escribiendo el Nº 0\n";


//Array con strings que devuelven una respuesta
const ARRAY_LLAVES_MAESTRAS = [
    /*[
        "name"      => "Luke", //palabra a la que responder
        "respuesta" => "Que la fuerza te acompañe", //la respuesta si no tiene funcion
        "funcion"   => false, // función para devolver una respuesta
        "similar"   => false, // Indicar si se permiten palabras similares
        "contiene"  => true, // Indicar si se reacciona a la plabra contenida en una frase
    ]*/
];


/*
* Array de los servicios disponibles
* dentro de cada servicio se encuentra un array de estados
* dentro de cada array de estados hay un array de opciones
* dentro de cada opcion hay un formulario que si no es false tendrá campos de formulario

########################################################################################################
################################# PARAMETROS QUE RECIBEN LAS FUNCIONES #################################
########################################################################################################

FUNCION              | RECIBE                              | RETRUN
------------------------------------------------------------------------------
check_user           | $userPhone                          | false o funcion boleana
check_admin          | $userPhone                          | false o funcion boleana
consulta             | $sesion, $data                      | string"false"(si da error), string"null"(si está vacía), string
formartear_respuesta | $consulta, $sesion, $data           | string
consulta_alt         | consulta_alt_cont[], $sesion, $data | string
check_send           | check_contiene[], $sesion, $data    | bol || (false || string)
------------------------------------------------------------------------------
[]) se define en ARRAY_SERVICIOS

*/
const ARRAY_SERVICIOS = [
    [
        "id_servicio"   => 1,
        "name_servicio" => "Redes Sociales \n",
        "text_servicio" => "Publica simultaneamente en Facebook, Instagram y en web tus productos \n",
        "check_user"    => false,
        "check_admin"   => false,
        "array_estados" => [ 
            [
                "id_estado"      => 1,
                "name_estado"    => "Presentación Wairbot \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "¿Qué soy? \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 2,
                        "name_opcion"          => "Mis Ventajas \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 3,
                        "name_opcion"          => "¿En que me diferencio? \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 4,
                        "name_opcion"          => "¿Soy viable y escalable? \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 5,
                        "name_opcion"          => "¿Tengo algún plan complementario? \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 6,
                        "name_opcion"          => "¿Por qué mis desarrolladores se presentan a BFA? \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 7,
                        "name_opcion"          => "Presentación Completa \n",
                        "consulta"             => "RedesSociales::presentacion",
                        "formartear_respuesta" => "RedesSociales::formateado",
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ]
                ]
            ]
        ]
    ]
];



const _enero = 'enero';
const _febrero = 'febrero';
const _marzo = 'marzo';
const _abril = 'abril';
const _mayo = 'mayo';
const _junio = 'junio';
const _julio = 'julio';
const _agosto = 'agosto';
const _septiembre = 'septiembre';
const _octubre = 'octubre';
const _noviembre = 'noviembre';
const _diciembre = 'diciembre';

const ARRAY_MESES = [_enero, _febrero, _marzo, _abril, _mayo, _junio, _julio, _agosto, _septiembre, _octubre, _noviembre, _diciembre];