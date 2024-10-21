<?php

return [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'holy_bible',
    'username' => 'root',
    'password' => 'rootpswd',
    'storage' => storage_path('app/tntsearch_indexes'),
    'stemmer' => \TeamTNT\TNTSearch\Stemmer\PorterStemmer::class,
    "charset"   => 'utf8',
    "collation" => 'utf8_general_ci',
];
