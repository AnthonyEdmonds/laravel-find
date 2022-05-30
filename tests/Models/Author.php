<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Models;

use AnthonyEdmonds\LaravelFind\Findable;
use AnthonyEdmonds\LaravelFind\Tests\Factories\AuthorFactory;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use Findable;
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    // Factory
    protected static function newFactory(): Factory
    {
        return new AuthorFactory();
    }

    // Laravel Find
    public static function findTypeLabel(): string
    {
        return 'Authors by name and books';
    }

    public static function canBeFoundBy(?Model $user): bool
    {
        return true;
    }
    
    protected static function findLabel(): string
    {
        return 'name';
    }

    protected static function findDescription(): string
    {
        return '"An author"';
    }

    protected static function findLink(): string
    {
        return 'https://my-link/~id';
    }

    protected static function findFilters(Builder $query, string $term, ?Model $user = null): Builder
    {
        return $query->where('name', 'LIKE', "%$term%");
    }
}
