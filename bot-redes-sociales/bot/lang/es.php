<?php
const _usuarioNoRegistrado = "No estás registrado para ninguno de nuestros servicios";
const _volver = 'volver';
const _bienvenido = "🤖 Bienvenido al Chat-Bot \n\n";
const _elegir = "🤖 Escribe uno de estos números:\n\n";
const _error_elegir = "🤖⚠ Debes escribir uno de los números de esta lista \n\n";
const _opcion_volver = "\n❌ 0.- Volver al Menú Anterior \n";
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
        "check_user"    => "RedesSociales::comprobarUsuario",
        "check_admin"   => false,
        "array_estados" => [
            [
                "id_estado"      => 1,
                "name_estado"    => "📲 Añadir un producto \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "📲 Añadir un producto \n",
                        "consulta"             => "RedesSociales::publicar",
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "🤖! Su publicación se ha realizado correctamente \n\n Puedes seguir añadiendo productos \n\n",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "🤖! Disculpe, No hemos podido realizar la publicación por motivos técnicos, por favor, vuelva a intentarlo más tarde \n",
                        "respuesta_error_alt"  => "",
                        "formulario"           => [
                            [
                                "id_campo"        => 1,
                                "resumen"         => false,
                                "campo"           => "\n¿Que tipo de producto quieres añadir?\n\n Ej.: Zapato mujer, cafetera, juguete, etc... \n\nEscribe la palabra NO si no quieres incluir el tipo de producto \n",
                                "key"             => "tipo",
                                "respuesta_error" => "",
                                "check_send"      => false,
                                "check_contiene"  => [],
                            ],[
                                "id_campo"        => 2,
                                "resumen"         => false,
                                "campo"           => "\n¿Cual es el nombre del producto?\n\n Puedes incluir un nombre comercial o un breve nombre descriptivo \n\n(Este campo es obligatorio)\n",
                                "key"             => "nombre",
                                "respuesta_error" => "",
                                "check_send"      => false,
                                "check_contiene"  => [],
                            ],[
                                "id_campo"        => 3,
                                "resumen"         => false,
                                "campo"           => "\nDescribe tu producto\n\n Puedes incluir cualquier texto descriptivo y detallado, características, tallas, colores, precio, etc... \n\nEscribe la palabra NO si no quieres incluir el tipo de producto \n",
                                "key"             => "descripcion",
                                "respuesta_error" => "",
                                "check_send"      => false,
                                "check_contiene"  => [], 
                            ],[
                                "id_campo"        => 4,
                                "resumen"         => false,
                                "campo"           => "\n📷 Haz una bonita foto de tu producto\n\n(Este campo es obligatorio)\n",
                                "key"             => "imagen",
                                "respuesta_error" => "\n🤖⚠ Ha ocurrido un error, debes enviarnos una foto realizada con tu dispositivo o una imágen en jpg o png\n",
                                "check_send"      => "RedesSociales::checkImage",
                                "check_contiene"  => [],
                            ]
                        ]
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