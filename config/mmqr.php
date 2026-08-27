<?php

return [
    'environment' => env('MMQR_ENV', 'uat'),

    'uat' => [
        'base_url'  => env('MMQR_UAT_BASE_URL'),
        'mid'       => env('MMQR_UAT_MID'),
        'tid'       => env('MMQR_UAT_TID'),
        'user_id'   => env('MMQR_UAT_USER_ID'),
        'source_id' => env('MMQR_UAT_SOURCE_ID'),
        'password'  => env('MMQR_UAT_PASSWORD'),
    ],

    'production' => [
        'base_url'  => env('MMQR_PROD_BASE_URL'),
        'mid'       => env('MMQR_PROD_MID'),
        'tid'       => env('MMQR_PROD_TID'),
        'user_id'   => env('MMQR_PROD_USER_ID'),
        'source_id' => env('MMQR_PROD_SOURCE_ID'),
        'password'  => env('MMQR_PROD_PASSWORD'),
    ],
];