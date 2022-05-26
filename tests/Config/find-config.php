<?php

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\Models\Book;
use AnthonyEdmonds\LaravelFind\Tests\Models\Chapter;

return [
    /* The key and label for searching all models; set as false to disable */
    'anything-key' => 'any',
    'anything-label' => 'Anything',
    
    /* A table that is guaranteed to exist */
    'base-table' => 'authors',
    
    /* Which models can be searched for, and its display label */
    'models' => [
        Author::class => 'Authors by name and books',
        Book::class => 'Books by title, author, and chapters',
        Chapter::class => 'Chapters by name, number, and book',
    ],
];
