<?php
/**
 * Project specific config file.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Prohori'),
    'project' => env('PROJECT', 'Prohori'),
    'project_key' => env('PROJECT_KEY'),  // prohori

    'project_namespace' => env('PROJECT_NAMESPACE'), // "App\\Projects\\${PROJECT}"
    'project_directory' => env('PROJECT_DIRECTORY'), // "app/Projects/${PROJECT}"

    'project_resource' => env('PROJECT_RESOURCE'), // "projects.${PROJECT_KEY}"
    'project_public_directory' => env('PROJECT_PUBLIC_DIRECTORY'), // "projects/${PROJECT_KEY}"
    'project_config' => env('PROJECT_CONFIG'), //"projects.${PROJECT_KEY}.config"

    /*
    |--------------------------------------------------------------------------
    | File upload directory
    |--------------------------------------------------------------------------
    | If the application uses local directory to store uploaded file then
    | it will use the following. The directory is relative to public.
    | Do not use a module name as directory name. This causes a
    | route url conflict.
    | public/files Note: don't use 'uploads' because it conflicts with uploads module
    |
    */

    'upload_root' => env('UPLOAD_ROOT', 'files'),

    /*
    |--------------------------------------------------------------------------
    | Date format
    |--------------------------------------------------------------------------
    | The format this used in showDate function
    |
    */

    'date_format' => env('DATE_FORMAT', 'd-m-Y'),
    'datetime_format' => env('DATETIME_FORMAT', 'd-m-Y H:i:s'),
    'js_date_format' => env('DATE_FORMAT_JS', 'd-m-Y'),
    'js_datetime_format' => env('DATETIME_FORMAT_JS', 'd-m-Y H:i:s'),

    /*
    |--------------------------------------------------------------------------
    | Query cache
    |--------------------------------------------------------------------------
    | Enable/Disable query cache.
    |
    */

    'query_cache' => env('QUERY_CACHE', false),

    /*
    |--------------------------------------------------------------------------
    | Load default mainframe routes
    |--------------------------------------------------------------------------
    | You can enable/disable loading of some of the mainframe default routes.
    */
    'routes' => [
        'web' => [
            // 'app/Mainframe/routes/auth.php',
            // 'app/Mainframe/routes/modules.php',
            // 'app/Mainframe/routes/web.php',
        ],
        'api' => [
            // 'app/Mainframe/routes/api.php',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Load additional CSS and JS file
    |--------------------------------------------------------------------------
    | Projects may have additional CSS/JS files which needs to be included
    */

    'load' => [
        'css' => [
            'projects/prohori/css/custom.css',
        ],
        'js' => [
            'projects/prohori/js/custom.js',
        ],
    ],
];