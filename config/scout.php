<?php

return [
    'driver' => env('SCOUT_DRIVER', 'tntsearch'),
    'tntsearch' => [
        'storage'  => storage_path('app/tntsearch_indexes'),
        'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
        'fuzzy' => [
            'prefix_length' => 2,
            'max_expansions' => 50,
            'distance' => 2
        ],
        'asYouType' => false,
        'searchBoolean' => env('TNTSEARCH_BOOLEAN', false),
        'index' => [
            'stemmers' => [
                \TeamTNT\TNTSearch\Stemmer\PorterStemmer::class,
            ],
        ],
    ],
];