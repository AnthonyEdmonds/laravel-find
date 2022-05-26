<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

abstract class Findable
{
    /* Column name of the primary identifier for the result, such as "name" */
    abstract public static function findLabel(): string;
    
    /* Column name or static label of a brief description for the result, such as "type" */
    abstract public static function findDescription(): string;
    
    /*
     * Static URL where the model can be found, or an array of URL parts to be replaced
     * "https://my-link.com"
     * OR
     * [
     *      'base' => https://my-link.com/~id/~other-id,
     *      '~id' => 'id-column',
     *      '~other-id' => 'other-column',
     * ]
     */
    abstract public static function findLink(): string|array;

    /* The filters, orders, and groups, to be applied to the search, such as "where('name', '=', $term)" */
    abstract public static function findBy(Builder $query, string $term): Builder;
    
    public static function find(string $term): Builder
    {
        $query = DB::table(static::tableName())
            ->select([
                static::findLabel() . ' AS label',
                static::findDescription() . ' AS description',
                static::findLink() . ' AS link',
            ]);
        
        return static::findBy($query, $term);
    }
    
    public static function tableName(): string
    {
        $model = new static();
        return $model->getTable();
    }
}
