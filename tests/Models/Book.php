<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Models;

use AnthonyEdmonds\LaravelFind\Findable;
use AnthonyEdmonds\LaravelFind\Tests\Factories\BookFactory;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use Findable;
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    // Factory
    protected static function newFactory(): Factory
    {
        return new BookFactory();
    }

    // Laravel Find
    public static function findTypeLabel(): string
    {
        return 'Books by title, author, and chapters';
    }

    public static function canBeFoundBy(?Model $user): bool
    {
        return false;
    }

    protected static function findLabel(): string
    {
        return 'title';
    }

    protected static function findDescription(): string
    {
        return '"A book"';
    }

    protected static function findLink(): string
    {
        return 'https://my-site.com/books/~id';
    }

    protected static function findFilters(Builder $query, string $term, ?Model $user = null): Builder
    {
        return $query->where('title', 'LIKE', "%$term%");
    }
}
