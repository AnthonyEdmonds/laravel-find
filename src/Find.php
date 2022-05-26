<?php

namespace AnthonyEdmonds\LaravelFind;

class Find
{
    protected string $term;
    protected array $queries = [];
    
    public function __construct(string $term)
    {
        $this->term = $term;
    }

    public function addModel(string $modelClass): static
    {
        $this->queries[] = $modelClass::findQuery($this->term);
    }
}
