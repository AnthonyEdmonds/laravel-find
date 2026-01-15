<?php

namespace AnthonyEdmonds\LaravelFind\Tests;

use AnthonyEdmonds\LaravelFind\Finder;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestFinder extends Finder
{
    public const string DEFAULT_FILTER = 'author';

    public const string DEFAULT_STATUS = 'open';

    public const string DEFAULT_SORT = 'oldest';

    public function search(): ResourceCollection
    {
        return new ResourceCollection([]);
    }

    public function filterLabel(string $currentFilter): string
    {
        return match ($currentFilter) {
            'author' => '~status I created ~sort',
            default => '~status ~sort',
        };
    }

    public function statusLabel(string $currentStatus): string
    {
        return match ($currentStatus) {
            'open' => 'open requests',
            default => 'all requests',
        };
    }

    public function sortLabel(string $currentSort): string
    {
        return match ($currentSort) {
            'oldest' => 'by oldest',
            default => 'by newest',
        };
    }

    public function listFilters(): array
    {
        return [
            'author' => 'Only show requests I created',
            'all' => 'Show all requests',
        ];
    }

    public function listSorts(): array
    {
        return [
            'newest' => 'Order by newest',
            'oldest' => 'Order by oldest',
        ];
    }

    public function listStatuses(): array
    {
        return [
            'all' => 'View all request statuses',
            'open' => 'View open requests',
        ];
    }

    public function route(): string
    {
        return 'requests.index';
    }
}
