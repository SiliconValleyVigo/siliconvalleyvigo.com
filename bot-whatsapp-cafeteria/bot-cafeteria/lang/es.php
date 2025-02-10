<?php
const _usuarioNoRegistrado = "No estás registrado para ninguno de nuestros servicios";
const _volver = 'volver';
const _bienvenido = "🤖 Bienvenido al Chat-Bot de tu Cafetería \n Estás en la mesa 7 \n\n";
const _elegir = "🤖 Escribe uno de estos números:\n\n";
const _error_elegir = "🤖⚠ Debes escribir uno de los números de esta lista \n\n";
const _opcion_volver = "\n❌ 0.- Volver al Menú Anterior \n";
const _volver_en_cualquier_momento = "\n❌ Puedes VOLVER al menú escribiendo el Nº 0\n";

const _sinDocumentosConEsaReferencia = "🤖⚠ No hay documentos disponibles con esa referencia\n";
const _sinDocumentos = "🤖⚠ No hay documentos disponibles\n";
const _sinDocumentosDeEsaFecha = "🤖⚠ No hay documentos disponibles con la fecha indicada\n";


//Array con strings que devuelven una respuesta
const ARRAY_LLAVES_MAESTRAS = [
    /*[
        "name"      => "Luke", //palabra a la que responder
        "respuesta" => "Que la fuerza te acompañe", //la respuesta si no tiene funcion
        "funcion"   => false, // función para devolver una respuesta
        "similar"   => false, // Indicar si se permiten palabras similares
        "contiene"  => true, // Indicar si se reacciona a la plabra contenida en una frase
    ]*/];


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
check_user           | $userPhone                          | bol
check_admin          | $userPhone                          | bol
consulta             | $sesion, $data                      | string"false"(si da error), string"null"(si está vacía), string
formartear_respuesta | $consulta, $sesion, $data           | string
consulta_alt         | consulta_alt_cont[], $sesion, $data | string
check_send           | check_contiene[], $sesion, $data    | bol || (false || string)
------------------------------------------------------------------------------
[]) se define en ARRAY_SERVICIOS

*/
const ARRAY_SERVICIOS = [
    [
        "id_servicio"   => 1, //DEBE ESTAR DENTRO DE LA BASE DE DATOS EN admin-bot -> AD_tipo_servicio_
        "name_servicio" => "cafeteria",
        "text_servicio" => "cafeteria",
        "check_user"    => false,
        "check_admin"   => false,
        "array_estados" => [
            [
                "id_estado"      => 1,
                "name_estado"    => "Ver la Carta \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "Croquetas \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 2,
                        "name_opcion"          => "Pincho de Tortilla \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 3,
                        "name_opcion"          => "Calamares \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 4,
                        "name_opcion"          => "Patatas Bravas \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ]
                ]
            ], [
                "id_estado"      => 2,
                "name_estado"    => "Bebidas y refrescos \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "Caña de Cerveza \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 2,
                        "name_opcion"          => "Bock de Cerveza \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 3,
                        "name_opcion"          => "Botella Estrella Galicia \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 4,
                        "name_opcion"          => "Botella Milnueve, Estrella Galicia 1906 \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 5,
                        "name_opcion"          => "Cocacola \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 6,
                        "name_opcion"          => "Fanta Naranja\n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 7,
                        "name_opcion"          => "Fanta Limón \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 8,
                        "name_opcion"          => "Agua \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 9,
                        "name_opcion"          => "Agua con gas \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias por su pedido, en breve se lo llevaremos a su mesa",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ]
                ]
            ], [
                "id_estado"      => 3,
                "name_estado"    => "Cafés e infusiones \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "Café Solo \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 2,
                        "name_opcion"          => "Café con Leche \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 3,
                        "name_opcion"          => "Café Cortado \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 4,
                        "name_opcion"          => "Infusión de Té Verde \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 5,
                        "name_opcion"          => "Infusión de Té Negro \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ],[
                        "id_opcion"            => 6,
                        "name_opcion"          => "Infusión de Manzanilla \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ]
                ]
            ], [
                "id_estado"      => 4,
                "name_estado"    => "Llamar al camarero \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "Gracias, el camarero se dirige a su mesa \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias! en seguida le atenderemos",
                        "respuesta_vacia"      => "",
                        "respuesta_error"      => "",
                        "respuesta_error_alt"  => "",
                        "formulario"           => false
                    ]
                ]
            ], [
                "id_estado"      => 5,
                "name_estado"    => "Pedir la cuenta \n",
                "array_opciones" => [
                    [
                        "id_opcion"            => 1,
                        "name_opcion"          => "Gracias por su visita, enseguida le traemos la cuenta \n",
                        "consulta"             => false,
                        "formartear_respuesta" => false,
                        "consulta_alt"         => false,
                        "consulta_alt_cont"    => [],
                        "respuesta_ok"         => "Gracias, enseguida le llevan la cuenta a su mesa",
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
