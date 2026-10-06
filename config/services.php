<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost/auth/google/callback'),
    ],

    'xpressbees' => [
        'client_name' => env('XPRESSBEES_CLIENT_NAME', 'Onestall'),
        'username'    => env('XPRESSBEES_USERNAME', 'admin@Onestall.com'),
        'password'    => env('XPRESSBEES_PASSWORD', 'Xpress@1234567'),
        'secret_key'  => env('XPRESSBEES_SECRET_KEY', '4a33a0c45de62425fd66d57da2c92896388ba8e52a2a3c3518194e4e4ea87d6c'),
        'xb_key'      => env('XPRESSBEES_XB_KEY', 'MkgNd38914BgtYp'),
        'endpoints'   => [
            'token'          => 'https://userauthapis.xbees.in/api/auth/generateToken',
            'manifest'       => 'https://apishipmentmanifestation.xbees.in/shipmentmanifestation/forward',
            'awb_series'     => 'https://xbclientapi.xbees.in/POSTShipmentService.svc/AWBNumberSeriesGeneration',
            'rto_notify'     => 'https://clientshipupdatesapi.xbees.in/forwardcancellation',
            'ndr_update'     => 'https://clientshipupdatesapi.xbees.in/client/UpdateNDRDeferredDeliveryDate',
            'awb_generated'  => 'https://xbclientapi.xbees.in/TrackingService.svc/GetAWBNumberGeneratedSeries',
            'serviceability' => 'https://xbmasterapi.xbees.in/expose/get/serviceabilitypincode/details',
            'tracking_audit' => 'https://apishipmenttracking.xbees.in/GetShipmentAuditLog',
            'tracking_status'=> 'https://apishipmenttracking.xbees.in/GetCurrentShipmentStatus',
        ]
    ],

];
