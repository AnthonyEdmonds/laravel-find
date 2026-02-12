<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FinderOutput
{
    public function __construct(
        public string $caption,
        public ResourceCollection $results,
        public string $currentFilter,
        public string $currentSearch,
        public string $currentSort,
        public string $currentStatus,
        public array $filters,
        public array $searchable,
        public array $sorts,
        public array $statuses,
        public string $searchLink,
        public FinderLink $clear,
    ) {
        //
    }
}
