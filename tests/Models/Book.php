<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Models;

use AnthonyEdmonds\LaravelFind\Findable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use Findable;

    protected $fillable = [
        'title',
        'author_id',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Laravel Find
    public static function canBeFoundBy(?Model $user): bool
    {
        return $user->can('find_books');
    }
    
    protected static function findLabel(): string
    {
        return 'title';
    }

    protected static function findDescription(): string
    {
        return 'A page from book';
    }

    protected static function findLink(): string
    {
        return route('pages.show', '~id');
    }

    protected static function findBy(Builder $query, string $term): Builder
    {
        return $query
            ->where('name', '=', $term)
            ->orWhere('name', '=', $term);
    }
}