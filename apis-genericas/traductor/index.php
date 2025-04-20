<?php
/*
{
    "source_language": "es",
    "target_language": "351",
    "text": "Hola Mundo"
}
*/

$apiKey = getenv('TRANSLATOR_API_KEY');
$apiHost = getenv('TRANSLATOR_HOST');
$endpoint = 'https://text-translator2.p.rapidapi.com/translate';

function translateText($sourceLanguage, $targetLanguage, $text) {
    global $apiKey, $apiHost, $endpoint;

    $encodedParams = http_build_query([
        'source_language' => $sourceLanguage,
        'target_language' => $targetLanguage,
        'text' => $text,
    ]);

    $headers = [
        'Content-Type: application/x-www-form-urlencoded',
        'X-RapidAPI-Key: ' . $apiKey,
        'X-RapidAPI-Host: ' . $apiHost,
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $apiResponse = curl_exec($ch);

    if (curl_errno($ch)) {
        $apiResponse = json_encode(['error' => curl_error($ch)]);
    }

    curl_close($ch);

    return $apiResponse;
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $requestData = file_get_contents('php://input');

    // Decode the JSON data
    $data = json_decode($requestData, true);

    if ($data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
    } else {
        // Extract the required parameters from the JSON data
        $sourceLanguage = isset($data['source_language']) ? $data['source_language'] : 'en';
        $targetLanguage = isset($data['target_language']) ? $data['target_language'] : 'es';
        $text = isset($data['text']) ? $data['text'] : '';

        // Convert the source_language code to its equivalent language code
        $targetLanguage = obtenerCodigoIdioma($targetLanguage);

        // Call the translation function
        if($sourceLanguage !== $targetLanguage){
            $translationResult = translateText($sourceLanguage, $targetLanguage, $text);
        }else{
            $translationResult = $text;
        }

        // Return the translation result as JSON response
        header('Content-Type: application/json');
        echo $translationResult;
    }
} else {
    // If the request method is not POST, return an error response
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}



function obtenerCodigoIdioma($codigo) {
    $codigosIdioma = array(
    '27' => 'af',  // Sudáfrica
    '355' => 'sq', // Albania
    '251' => 'am', // Etiopía
    '20' => 'ar',  // Egipto
    '374' => 'hy', // Armenia
    '994' => 'az', // Azerbaiyán
    '375' => 'be', // Bielorrusia
    '880' => 'bn', // Bangladesh
    '387' => 'bs', // Bosnia y Herzegovina
    '359' => 'bg', // Bulgaria
    '63' => 'ceb', // Filipinas
    '265' => 'ny', // Malawi
    '86' => 'zh-CN', // China
    '886' => 'zh-TW', // Taiwán
    '385' => 'hr', // Croacia
    '420' => 'cs', // República Checa
    '45' => 'da',  // Dinamarca
    '31' => 'nl',  // Países Bajos
    '1' => 'en',   // Estados Unidos y Canadá
    '672' => 'eo', // Islas Cocos (Keeling)
    '372' => 'et', // Estonia
    '358' => 'fi', // Finlandia
    '33' => 'fr',  // Francia
    '995' => 'ka', // Georgia
    '49' => 'de',  // Alemania
    '30' => 'el',  // Grecia
    '91' => 'gu',  // India
    '509' => 'ht', // Haití
    '972' => 'iw', // Israel
    '856' => 'hmn', // Laos
    '36' => 'hu',  // Hungría
    '354' => 'is', // Islandia
    '62' => 'id',  // Indonesia
    '353' => 'ga', // Irlanda
    '39' => 'it',  // Italia
    '81' => 'ja',  // Japón
    '62' => 'jw',  // Indonesia (javanés)
    '7' => 'kk',   // Kazajistán y Rusia
    '855' => 'km', // Camboya
    '250' => 'rw', // Ruanda
    '82' => 'ko',  // Corea del Sur
    '964' => 'ku', // Irak (kurdo)
    '996' => 'ky', // Kirguistán
    '856' => 'lo', // Laos
    '379' => 'la', // Ciudad del Vaticano
    '371' => 'lv', // Letonia
    '370' => 'lt', // Lituania
    '352' => 'lb', // Luxemburgo
    '389' => 'mk', // Macedonia del Norte
    '261' => 'mg', // Madagascar
    '60' => 'ms',  // Malasia
    '356' => 'mt', // Malta
    '64' => 'mi',  // Nueva Zelanda (maorí)
    '91' => 'mr',  // India (maratí)
    '976' => 'mn', // Mongolia
    '95' => 'my',  // Myanmar (birmano)
    '977' => 'ne', // Nepal
    '47' => 'no',  // Noruega
    '93' => 'ps',  // Afganistán (pastún)
    '98' => 'fa',  // Irán (persa)
    '48' => 'pl',  // Polonia
    '351' => 'pt', // Portugal
    '40' => 'ro',  // Rumania
    '685' => 'sm', // Samoa
    '44' => 'en',  // Reino Unido (gaélico escocés)
    '381' => 'sr', // Serbia
    '266' => 'st', // Lesoto (sesoto)
    '263' => 'sn', // Zimbabue (shona)
    '92' => 'sd',  // Pakistán (sindhi)
    '94' => 'si',  // Sri Lanka
    '421' => 'sk', // Eslovaquia
    '252' => 'so', // Somalia
    '34' => 'es',  // España
    '255' => 'sw', // Tanzania
    '46' => 'sv',  // Suecia
    '992' => 'tg', // Tayikistán
    '91' => 'ta',  // India (tamil)
    '7' => 'tt',   // Rusia (tártaro)
    '91' => 'te',  // India (telugu)
    '66' => 'th',  // Tailandia
    '90' => 'tr',  // Turquía
    '993' => 'tk', // Turkmenistán
    '380' => 'uk', // Ucrania
    '92' => 'ur',  // Pakistán (urdu)
    '998' => 'uz', // Uzbekistán
    '84' => 'vi',  // Vietnam
    '972' => 'yi', // Israel (yídish)
    '234' => 'yo', // Nigeria (yoruba)
);

    // Verificar si el código existe en el array
    if (isset($codigosIdioma[$codigo])) {
        return $codigosIdioma[$codigo];
    } else {
        return 'en';
    }
}
