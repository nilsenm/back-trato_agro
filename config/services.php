<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Consulta Externa API (DNI/RUC)
    |--------------------------------------------------------------------------
    |
    | Configuración para la API de consulta de DNI y RUC.
    | Por defecto usa facturacionelectronica.us, pero puedes cambiarlo
    | mediante variables de entorno.
    |
    */

    'consulta_externa' => [
        'usuario' => env('CONSULTA_EXTERNA_USUARIO', '10447915125'),
        'password' => env('CONSULTA_EXTERNA_PASSWORD', '985511933'),
        'base_url' => env('CONSULTA_EXTERNA_BASE_URL', 'https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php'),
        
        // APIs alternativas gratuitas (actualmente bloqueadas)
        'api_reniec_cloud' => env('CONSULTA_EXTERNA_RENIEC_CLOUD', 'https://api.reniec.cloud/dni/'),
        'api_searchpe' => env('CONSULTA_EXTERNA_SEARCHPE', 'https://searchpe.herokuapp.com/public/api/dni/'),
        'api_sunat_cloud' => env('CONSULTA_EXTERNA_SUNAT_CLOUD', 'https://api.sunat.cloud/ruc/'),
        
        // APIs de pago (requieren suscripción)
        'json_pe_token' => env('JSON_PE_TOKEN', ''),
        'json_pe_url' => env('JSON_PE_URL', 'https://json.pe/api/v1/'),
        'apiperu_token' => env('APIPERU_TOKEN', ''),
        'apiperu_url' => env('APIPERU_URL', 'https://apiperu.pro/api/'),
        
        // API apis.net.pe (funcional)
        'apis_net_pe_token' => env('APIS_NET_PE_TOKEN', 'apis-token-7799.ryyt1IDYE8o6qGdjRJi5PuGXz9q6bG9I'),
        'apis_net_pe_url' => env('APIS_NET_PE_URL', 'https://api.apis.net.pe/v2/'),
    ],

];


