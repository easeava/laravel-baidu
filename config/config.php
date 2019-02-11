<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'defaults' => [
        'response_type' => 'array',

        'use_laravel_cache' => true,

        'log' => [
            'level' => env('BAIDU_LOG_LEVEL', 'debug'),
            'file' => env('BAIDU_LOG_FILE', storage_path('logs/baidu.log')),
        ],
    ],

    'route' => [

    ],

    'bear_tp' => [
        'default' => [
            'client_id' => '',
            'client_secret' => '',
            'token' => '',
            'aes_key' => '',
        ],
    ],

    'bear' => [
        'default' => [
            'client_id' => '',
            'client_secret' => '',
            'token' => '',
            'aes_key' => '',

//            'oauth' => [
//                'scopes' => ['snsapi_userinfo'],
//                'callback' => '/oauth/callback',
//            ],
        ],
    ],

    'smart_tp' => [
        'default' => [
            'client_id' => '',
            'token' => '',
            'app_key' => '',
            'aes_key' => '',
        ],
    ],

    'smart_program' => [
        'default' => [
            'app_id' => '',
            'app_secret' => '',
            'app_key' => '',
            'token' => '',
        ],
    ],
];