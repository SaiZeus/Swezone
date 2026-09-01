<?php

return [
    'environment' => env('MMQR_ENVIRONMENT', 'uat'),

    'uat' => [
        'base_url'  => env('MMQR_UAT_BASE_URL', 'https://uat-app.cbbank.com.mm:6007/dynamicqrapi/v1'),
        'user_id'   => env('MMQR_UAT_USER_ID', 'swezon'),
        'password'  => env('MMQR_UAT_PASSWORD', 'Fas0h^oUc-}zK61m6Eo3W£%JD'),
        'source_id' => env('MMQR_UAT_SOURCE_ID', 'swezon'),
        'mid'       => env('MMQR_UAT_MID', '223780111437170'),
        'tid'       => env('MMQR_UAT_TID', '52912778'),
    ],

    'production' => [
        'base_url'  => env('MMQR_PROD_BASE_URL', 'https://myanmarpay.cbbank.com.mm:7443/dynamicqrapi/v1'),
        'user_id'   => env('MMQR_PROD_USER_ID', 'swezon'),
        'password'  => env('MMQR_PROD_PASSWORD', 'G1E3N]T09IIVwy}E*?Vniu6B#'),
        'source_id' => env('MMQR_PROD_SOURCE_ID', 'swezon'),
        'mid'       => env('MMQR_PROD_MID', '223780111255150'),
        'tid'       => env('MMQR_PROD_TID', '52000080'),
    ],
];