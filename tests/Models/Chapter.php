<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Models;

use AnthonyEdmonds\LaravelFind\Findable;
use AnthonyEdmonds\LaravelFind\Tests\Factories\ChapterFactory;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use Findable;
    use HasFactory;

    protected $fillable = [
        'title',
        'number',
        'book_id',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Factory
    protected static function newFactory(): Factory
    {
        return new ChapterFactory();
    }

    // Laravel Find
    public static function findTypeLabel(): string
    {
        return 'Chapters by name, number, and book';
    }

    public static function canBeFoundBy(?Model $user): bool
    {
        return true;
    }
    
    protected static function findLabel(): string
    {
        return 'chapters.title';
    }

    protected static function findDescription(): string
    {
        return 'Chapter ~number of ~books.title';
    }

    protected static function findLink(): string
    {
        return 'https://my-site.com/books/~book_id/~number';
    }

    protected static function findFilters(Builder $query, string $term, ?Model $user = null): Builder
    {
        return $query
            ->leftJoin('books', 'books.id', '=', 'chapters.book_id')
            ->where('chapters.title', 'LIKE', "%$term%")
            ->orWhere('chapters.number', '=', $term)
            ->orWhere('books.title', 'LIKE', "%$term%");
    }
}
