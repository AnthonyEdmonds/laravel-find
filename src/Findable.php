<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Findable
{
    /* The display label for the Model type, such as "Authors by name and book title" */
    abstract public static function findTypeLabel(): string;

    /* Whether the model type can be found by the viewing User */
    abstract public static function canBeFoundBy(?Model $user): bool;

    /*
     * The primary identifier for the result, which can be:
     * A column name, such as 'title'
     * A static description, such as '"Book"'
     * A sentence with placeholders, such as '~title by ~author'
     */
    abstract protected static function findLabel(): string;

    /*
     * A brief description of the result, which can be:
     * A column name, such as 'description'
     * A static description, such as '"A collection of pages"'
     * A sentence with placeholders, such as '~genre ~page_count'
     */
    abstract protected static function findDescription(): string;

    /*
     * The URL where the result can be found, which can be:
     * A column name, such as 'url'
     * A static link, such as '"https://my-site.com/users"'
     * A sentence with placeholders, such as 'https://my-site.com/books/~book_id/~id'
     */
    abstract protected static function findLink(): string;

    /* The to be applied to the search, such as "where('name', '=', $term)" */
    abstract protected static function findFilters(Builder $query, string $term, ?Model $user = null): Builder;

    public static function excludeFromFindAny(): bool
    {
        return false;
    }

    /* Build a query to find a specific model of this type */
    public static function findBy(string $term): Builder
    {
        $query = DB::table(static::tableName())
            ->select([
                DB::raw(static::replacePlaceholders(static::findLabel(), '~', ' ').' AS label'),
                DB::raw(static::replacePlaceholders(static::findDescription(), '~', ' ').' AS description'),
                DB::raw(static::replacePlaceholders(static::findLink(), '~', '/').' AS link'),
            ]);

        return static::findFilters($query, $term, Auth::user());
    }

    /* Get the table name of the current model */
    public static function tableName(): string
    {
        $model = new static();

        return $model->getTable();
    }

    /* Replace link placeholders */
    protected static function replacePlaceholders(
        string $link,
        string $startToken,
        string $endToken
    ): string {
        $placeholders = static::findPlaceholders($link, $startToken, $endToken);
        $sql = '';

        if (empty($placeholders) !== false) {
            return $link;
        }

        foreach ($placeholders as $index => $placeholder) {
            $sql = 'REPLACE('.$sql;

            $index === 0
                ? $sql .= '"'.$link.'",'
                : $sql .= ',';

            $sql .= '"'.$placeholder.'",';
            $sql .= substr($placeholder, 1);

            $sql .= ')';
        }

        return $sql;
    }

    /* Find all placeholders within a string */
    protected static function findPlaceholders(
        string $haystack,
        string $startToken,
        string $endToken
    ): array {
        $cursor = 0;
        $placeholders = [];

        if (str_contains($haystack, $startToken) !== true) {
            return $placeholders;
        }

        while ($cursor !== false) {
            $cursor = strpos($haystack, $startToken, $cursor);
            if ($cursor === false) {
                break;
            }
            
            $endIndex = strpos($haystack, $endToken, $cursor);

            $placeholders[] = substr(
                $haystack,
                $cursor,
                $endIndex !== false
                    ? ($endIndex - $cursor)
                    : null
            );

            $cursor = $endIndex;
        }

        return $placeholders;
    }
}
