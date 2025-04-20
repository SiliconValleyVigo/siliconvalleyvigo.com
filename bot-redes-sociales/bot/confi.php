<?php
const MAIL_PASSWORD_RECOVERY = getenv('BOT_MAIL_RECOVERY');
const JWT_KEY = getenv('BOT_JWT_KEY');
const API_KEY = getenv('BOT_API_KEY');
const REDES_SOCIALES_KEY = getenv('BOT_SOCIAL_KEY');
const DB_NAME = getenv('BOT_DB_NAME');
const DB_USER = getenv('BOT_DB_USER');
const DB_PASS = getenv('BOT_DB_PASSWORD');
const DB_HOST = getenv('DB_SERVER_DEFAULT_HOST');

/*
_URL                https://rodapro.eu/bot-whatsapp-archivos/
_TELEFONO_ADMIN     641429663
_KEY                PBdFD4FHSinwaabBY3BrPFny4g3PHMvtZ8bef9qkaxkGzbryXU
_API                bot-archivos/
*/

//Array de lenguajes disponibles, el lenguaje es definido por el prefijo del telefono
const ARRAY_LANGS = [
    [
        "prefix" => "34",
        "lang" => "es"
    ],
    [
        "prefix" => "default",
        "lang" => "es"
    ]
];

//Columnas extra para añadadir en la DB de ADMIN
# LOS NOMBRES QUE TERMINEN EN "_" SERÁN ELIMINADOS DEL FORMULARIO DE CONFIGURACIÓN #
# El nombre del campo despues del profijo y antes de "__" será el nombre del campo en el front
# ESTRUCTURA:  AD_nombre_de_la_columna_descriptivo__tipoDeInput__nombreDelServicio
const ARRAY_ADMIN_COLUMNS = [
    /*
    [
        "name" => "AD_url_tracking_",
        "sql" => "VARCHAR(255) NULL"
    ], [
        "name" => "AD_columna_uno__text__1",
        "sql" => "VARCHAR(255) NULL"
    ], [
        "name" => "AD_columna_2__text__2",
        "sql" => "VARCHAR(255) NULL"
    ], [
        "name" => "AD_columna_3__text__3",
        "sql" => "VARCHAR(255) NULL"
    ], [
        "name" => "AD_url_de_seguimiento_tracking_",
        "sql" => "VARCHAR(255) NULL"
    ], [
        "name" => "AD_aviso__text__1",
        "sql" => "VARCHAR(255) NULL"
    ]*/
];

//Columnas extra para añadadir en la DB de USER
#LOS NOMBRES QUE TERMINEN EN "_" SERÁN ELIMINADOS DEL FORMULARIO DE CONFIGURACIÓN#
const ARRAY_USER_COLUMNS = [
    [
        "name" => "US_mail",
        "sql" => "VARCHAR(255) NULL"
    ]
];

//Columnas extra para añadadir en la DB de USER
#LOS NOMBRES QUE TERMINEN EN "_" SERÁN ELIMINADOS DEL FORMULARIO DE CONFIGURACIÓN#
const ARRAY_SESION_COLUMNS = [
    [
        "name" => "SE_storage",
        "sql" => "LONGTEXT NULL"
    ]
];



