<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait Findable
{
    /* Whether the model type can be found by the viewing User */
    abstract public static function canBeFoundBy(?Model $user): bool;
    
    /* Column name of the primary identifier for the result, such as "name" */
    abstract protected static function findLabel(): string;
    
    /* Column name or static label of a brief description for the result, such as "type" */
    abstract protected static function findDescription(): string;
    
    /*
     * URL where the result can be found; use a tilde followed by a column name for value binding:
     * https://my-site.com/users/~id => http://my-site.com/users/1
     */
    abstract protected static function findLink(): string;

    /* The filters, orders, and groups, to be applied to the search, such as "where('name', '=', $term)" */
    abstract protected static function findBy(Builder $query, string $term): Builder;
    
    /* Build a query to find a specific model of this type */
    public static function find(string $term): Builder
    {
        // TODO Link ~id replacement
        // TODO Allow array from label / description?
        
        $query = DB::table(static::tableName())
            ->select([
                static::findLabel() . ' AS label',
                static::findDescription() . ' AS description',
                static::findLink() . ' AS link',
            ]);
        
        return static::findBy($query, $term);
    }
    
    /* Get the table name of the current model */
    public static function tableName(): string
    {
        $model = new static();
        return $model->getTable();
    }
}
