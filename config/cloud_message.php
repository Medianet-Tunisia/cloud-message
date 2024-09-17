<?php

return [
    /* This file is part of the medianet-dev/cloud-message package. */
    /* This is the configuration file for the CloudMessage package. */

    // Default driver to use
    'default_driver' => 'firebase',

    // Firebase settings
    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'path_to_service_account' => env('FIREBASE_PATH_TO_SERVICE_ACCOUNT', config_path('firebase.json')),
    ],

    // Huawei settings
    'huawei' => [
        'app_id' => env('HUAWEI_APP_ID'),
        'app_secret' => env('HUAWEI_APP_SECRET'),
        'grant_type' => env('HUAWEI_GRANT_TYPE', 'client_credentials'),
    ],

    // Enable or disable API logs
    'with_log' => env('CLOUD_MESSAGE_WITH_LOG', false),
];
