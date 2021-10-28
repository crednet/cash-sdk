<?php

return [
    'base_url' => 'https://localhost:8000/api/',
    'test' => [
        'secret_key' => '',

        'public_key' => '',
    ],
    'live' => [
        'secret_key' => '',

        'public_key' => '',
    ],

    'prefix' => 'api/cash',

    'middleware' => ['api'],
];
