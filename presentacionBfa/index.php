<?php

/************************************************
----------USO DESDE API DE MENSAJERÍA:----------
 * Esta Api recibe un json de una api de mensajería
 * {
    "key":"", //necesario para autorizar la petición.
    "text":"", //el mensaje.
    "userPhone":"", //el contacto del que envía el mensaje (usuario de una aplicación).
    "adminPhone":"" //el contacto del que recibe el mensaje (administrador de datos).
    }
 * Si el usuario está registrado en algún servicio de la api
   se generará una respuesta recogiendo los datos de una api
   propiedad de un administrador.
 * Servicios disponibles:
    - Asistente de compra para Prestashop

--------------USO DESDE FRONT WEB:--------------
 * Esta Api recibe un json de una web de
 * Estas son las opciones: //se usan opciones en lugar de rutas.
     - user-login
     - user-singup
     - user-passwordRecovery
     - user-controlPanel
     - user-changePassword
 * {
    "text":"",//Aquí va la opción
    "token":"", //necesario para controlPanel
    "AD_id_":"",
    "AD_email_":"",
    "AD_password_":"",
    "AD_password__change":"",
    "AD_validated_":"",
    "AD_nombre_del_servicio__text":"",
    "AD_admin_asunto":"",
    "AD_numero_de_telefono_del_bot__tel":"",
    "AD_numero_de_telefono_con_permiso_de_administrador__text":"",
    "AD_prefijo_internacional__number":"",
    "AD_URL":"",
    "AD_prestaKey":"",
    "AD_HTTP":"",
    "AD_WEB":"",
    "AD_folder_images":"",
    "AD_contacto_id":"",
    "AD_lenguaje_id":"",
    "AD_moneda_id":""
   }

--------------ATENCION:--------------
* Las imágenes se envían desde whatsapp en base64 sin la cabecera "data:image/jpeg;base64,".
* INCLUIR ARCHIVOS FUERA DE PLANTILLA que se almacenan en la carpeta class dentro de func.
* Se debe dar de alta de forma manual en la base de datos si la interfaz de administración no está disponible.
*************************************************/


error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once 'lang/SelectLang.php';
include_once 'confi.php';
include_once 'utils.php';
include_once 'func/userInWeb.php';
include_once 'func/sesion.php';
include_once 'func/user.php';
include_once 'func/conversacion.php';
include_once 'func/respuesta.php';
include_once 'func/llavesMaestras.php';
include_once 'DB/adminDB.php';
include_once 'DB/serviceDB.php';
include_once 'DB/userDB.php';
include_once 'DB/sesionDB.php';
include_once 'DB/logDB.php';

#############################################################################################
////////////////////////////INCLUIR ARCHIVOS FUERA DE PLANTILLA//////////////////////////////
#############################################################################################
include_once 'func/class/redesSociales.php';

global $conn;

#############################################################################################
/////////////////////////CREAR LAS TABLAS NECESARIAS SI NO EXISTEN///////////////////////////
#############################################################################################

AdminDb::createTable();
AdminDb::createColumnsExtra();

UserDb::createTable();
UserDb::createColumnsExtra();

SesionDb::createTable();
SesionDb::createColumnsExtra();

LogDb::createTable();


###########################################################################################
//////////////////////////OBTENER DATOS DE LA API DE MENSAJERÍA////////////////////////////
###########################################################################################

//headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
header("accept: application/json");

//obtener los datos de la api de mensajería (whatsapp)
$postData = file_get_contents('php://input');
$requests = !empty($postData) ? json_decode($postData, true) : array();

//comprobar que es un desarrollador autorizado
if(isset($requests['key']) === false || $requests['key'] !== API_KEY){
    echo "No estás autorizado";
    exit;
}

$text = $requests['text'];
$text = Utils::limpiarString($text);

###########################################################################################
////////////////////////////////////USER ESTÁ EN WEB//////////////////////////////////////
###########################################################################################
$isUserInWeb = UserInWeb::initUserInWeb($requests);

/*
****************añadir a todas las entradas en la base de datos real_escape_string*********************
****************aplicar el codigo de Marcos(mail) para validra y transformar los datos*****************
*/

###########################################################################################
/////////////////////////////////USER ESTÁ EN MENSAJERÍA///////////////////////////////////
###########################################################################################
if($isUserInWeb === false){
    $userPhone = User::formatNumber($requests['userPhone']);
    $adminPhone = $requests['adminPhone'];

    //SELECCIONAR IDIOMA POR EL PREFIJO DEL TELEFONO DEL USUARIO
    $langCode = SelectLang::InitSelectLang($requests);
    include_once ("lang/$langCode".'.php');

    $respuesta = $esMaestra = LlavesMaestras::initLlavesMaestras($requests);

    //OBTENER DATOS DE USUARIO
    $user = UserDb::createReadUser($userPhone);
    if($esMaestra !== false){
        echo $esMaestra;
    }else{
        $checkUser = User::checkUser($userPhone, $adminPhone);
        if($checkUser === true){
            //OBTENER RESPUESTAS
            $respuesta = $conversacion = Conversacion::proceso($user['US_id_'], $requests);
            echo $conversacion;
        }else{
            echo _bienvenido;
            echo _usuarioNoRegistrado;
        }
    }

    //Escribir la conversacion en el log
    LogDb::postLog($requests, $respuesta, $user['US_id_']);
}

mysqli_close($conn);
unset($conn);