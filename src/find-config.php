<?php

return [
    /* The key and label for searching all models; set as false to disable */
    'anything-key' => 'any',
    'anything-label' => 'Anything',

    /* A table that is guaranteed to exist */
    'base-table' => 'users',

    /* Which models can be searched for, and its display label */
    'models' => [
        'users' => \App\Models\User::class,
    ],
];
