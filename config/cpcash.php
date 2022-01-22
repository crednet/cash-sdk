<?php

return [
    'base_url' => env('CPCASH_BASEURL', 'https://localhost:8000/api/'),
    'test' => [
        'secret_key' => env('CPCASH_TEST_SECRET_KEY'),
        'public_key' => env('CPCASH_TEST_PUBLIC_KEY'),
    ],
    'live' => [
        'secret_key' => env('CPCASH_LIVE_SECRET_KEY'),
        'public_key' => env('CPCASH_LIVE_PUBLIC_KEY'),
    ],

    'prefix' => 'api/cash',

    'cards' => [
        'table' => 'personal_repayment_cards',
        'columns' => ['authorization_code', 'email']
    ],

    'middleware' => ['api'],

    'feature_access_top_up' => ['feature-access:cash-top-up'],
    'feature_access_withdraw' => ['feature-access:cash-withdrawal'],
	'credit_card_transaction' => '\App\Services\V2\CreditCard\CreditCardTransactionService',

    'user' => [
        'column' => 'user_id',
        'table' => 'users'
    ]
];
