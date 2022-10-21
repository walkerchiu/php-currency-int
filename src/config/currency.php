<?php

/**
 * @license MIT
 * @package WalkerChiu\Currency
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Switch association of package to On or Off
    |--------------------------------------------------------------------------
    |
    | When you set someone On:
    |     1. Its Foreign Key Constraints will be created together with data table.
    |     2. You may need to change the corresponding class settings in the config/wk-core.php.
    |
    | When you set someone Off:
    |     1. Association check will not be performed on FormRequest and Observer.
    |     2. Cleaner and Initializer will not handle tasks related to it.
    |
    | Note:
    |     The association still exists, which means you can still access related objects.
    |
    */
    'onoff' => [
        'core-lang_core' => 0,

        'account'   => 0,
        'group'     => 0,
        'rule'      => 0,
        'rule-hit'  => 0,
        'site-mall' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Lang Log
    |--------------------------------------------------------------------------
    |
    | 0: Don't keep data.
    | 1: Keep data.
    |
    */
    'lang_log' => 0,

    /*
    |--------------------------------------------------------------------------
    | Output Data Format from Repository
    |--------------------------------------------------------------------------
    |
    | null:                  Query.
    | query:                 Query.
    | collection:            Query collection.
    | collection_pagination: Query collection with pagination.
    | array:                 Array.
    | array_pagination:      Array with pagination.
    |
    */
    'output_format' => null,

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    */
    'pagination' => [
        'pageName' => 'page',
        'perPage'  => 15
    ],

    /*
    |--------------------------------------------------------------------------
    | Soft Delete
    |--------------------------------------------------------------------------
    |
    | 0: Disable.
    | 1: Enable.
    |
    */
    'soft_delete' => 1,

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Set default value for all package.
    |
    */
    'currency_id' => 1,

    /*
    |--------------------------------------------------------------------------
    | Command
    |--------------------------------------------------------------------------
    |
    | Location of Commands.
    |
    */
    'command' => [
        'cleaner'     => 'WalkerChiu\Currency\Console\Commands\CurrencyCleaner',
        'initializer' => 'WalkerChiu\Currency\Console\Commands\CurrencyInitializer'
    ],

    /*
    |--------------------------------------------------------------------------
    | Initializer
    |--------------------------------------------------------------------------
    */
    'initializer' => [
        [
            'abbreviation'  => 'USD',
            'mark'          => '$',
            'exchange_rate' => 1,
            'is_base'       => 1,
            'is_enabled'    => 1,
            'name'          => '美金'
        ],
        [
            'abbreviation'  => 'TWD',
            'mark'          => 'NT',
            'exchange_rate' => 29.9782,
            'is_base'       => 0,
            'is_enabled'    => 1,
            'name'          => '新台幣'
        ],
        [
            'abbreviation'  => 'HKD',
            'mark'          => '$',
            'exchange_rate' => 7.7540,
            'is_base'       => 0,
            'is_enabled'    => 1,
            'name'          => '港幣'
        ],
        [
            'abbreviation'  => 'MOP',
            'mark'          => 'P',
            'exchange_rate' => 7.9122,
            'is_base'       => 0,
            'is_enabled'    => 1,
            'name'          => '澳門幣'
        ],
        [
            'abbreviation'  => 'CNY',
            'mark'          => '¥',
            'exchange_rate' => 7.1108,
            'is_base'       => 0,
            'is_enabled'    => 1,
            'name'          => '人民幣'
        ],
        [
            'abbreviation'  => 'JPY',
            'mark'          => '¥',
            'exchange_rate' => 110.79,
            'is_base'       => 0,
            'is_enabled'    => 0,
            'name'          => '日圓'
        ],
        [
            'abbreviation'  => 'KRW',
            'mark'          => '₩',
            'exchange_rate' => 1224.89,
            'is_base'       => 0,
            'is_enabled'    => 0,
            'name'          => '韓圓'
        ],
        [
            'abbreviation'  => 'GBP',
            'mark'          => '£',
            'exchange_rate' => 0.84388,
            'is_base'       => 0,
            'is_enabled'    => 1,
            'name'          => '英鎊'
        ],
        [
            'abbreviation'  => 'AUD',
            'mark'          => '$',
            'exchange_rate' => 1.6856,
            'is_base'       => 0,
            'is_enabled'    => 0,
            'name'          => '澳幣'
        ],
        [
            'abbreviation'  => 'EUR',
            'mark'          => '€',
            'exchange_rate' => 0.91643,
            'is_base'       => 0,
            'is_enabled'    => 0,
            'name'          => '歐元'
        ],
        [
            'abbreviation'  => 'MYR',
            'mark'          => 'RM',
            'exchange_rate' => 4.3478,
            'is_base'       => 0,
            'is_enabled'    => 0,
            'name'          => '馬來幣'
        ],
        [
            'abbreviation'  => 'SGD',
            'mark'          => '$',
            'exchange_rate' => 1.4478,
            'is_base'       => 0,
            'is_enabled'    => 0,
            'name'          => '新加坡幣'
        ]
    ]
];
