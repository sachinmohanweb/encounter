<?php

return [
    'driver' => env('SCOUT_DRIVER', 'tntsearch'),
    'tntsearch' => [
        'storage'  => storage_path('app/tntsearch_indexes'),
        'fuzziness' => env('TNTSEARCH_FUZZINESS', true),
        'fuzzy' => [
            'prefix_length' => 4,
            'max_expansions' => 50,
            'distance' => 4
        ],
        'asYouType' => false,
        'searchBoolean' => env('TNTSEARCH_BOOLEAN', false)
    ],
];