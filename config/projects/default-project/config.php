<?php
/**
 * Project specific config file.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('PROJECT', 'DefaultProject'),

    /*
    |--------------------------------------------------------------------------
    | Default Email CCs
    |--------------------------------------------------------------------------
    |
    | Some of the emails will go out to a number of admin user.
    |
    */

    'default_email_recipients' => [
        'su@mainframe',
    ],

    /*
    |--------------------------------------------------------------------------
    | Section : Project specific custom config
    |--------------------------------------------------------------------------
    */

    'auto_cleanup_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | External APIs
    |--------------------------------------------------------------------------
    */

    // Facility registry API details
    'facility_registry' => [
        'url' => env('FACILITY_REGISTRY_URL'),
        'X-Auth-Token' => env('FACILITY_REGISTRY_X_AUTH_TOKEN'),
        'client-id' => env('FACILITY_REGISTRY_CLIENT_ID'),
    ]

];
