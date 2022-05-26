<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Models;

use AnthonyEdmonds\LaravelFind\Findable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use Findable;
    
    protected $fillable = [
        'name',
    ];
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Laravel Find
    public static function canBeFoundBy(?Model $user): bool
    {
        return $user->can('find_authors');
    }
    
    protected static function findLabel(): string
    {
        return 'name';
    }

    protected static function findDescription(): string
    {
        return 'User';
    }

    protected static function findLink(): string
    {
        return route('users.show', '~id');
    }

    protected static function findBy(Builder $query, string $term): Builder
    {
        return $query
            ->where('name', '=', $term)
            ->orWhere('name', '=', $term);
    }
}
