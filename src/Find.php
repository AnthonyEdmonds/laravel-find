<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Find
{
    /* Perform a search for a term */
    public static function find(string $term, string $modelClass): Builder
    {
        $modelsAllowed = array_keys(self::types());
        
        if (array_key_exists($modelClass, $modelsAllowed) === false) {
            $key = substr($modelClass, strrpos($modelClass, '\\'));
            throw new AuthorizationException("You do not have permission to find a $key");
        }

        return $modelClass === config('laravel-find.anything-key')
            ? self::findAnything($term, $modelsAllowed)
            : $modelClass::find($term);
    }
    
    /* Which models the current User can find */
    public static function types(): array
    {
        $anythingKey = config('laravel-find.anything-key');
        $models = config('laravel-find.models');
        $findable = [];
        $user = Auth::user();
        
        if ($anythingKey !== false) {
            $findable[$anythingKey] = config('laravel-find.anything-label');
        }
        
        foreach ($models as $modelClass => $label) {
            if ($modelClass::canBeFoundBy($user) === true) {
                $findable[$modelClass] = $label;
            }
        }
            
        return $findable;
    }
    
    /* A non-findable base query for findAnything to clamp onto */
    protected static function baseQuery(): Builder
    {
        return DB::table(config('laravel-find.base-table'))
            ->select([
                DB::raw('"Label" as label'),
                DB::raw('"Link" as link'),
                DB::raw('"Description" as description')
            ])
            ->whereRaw('1 = 2');
    }
    
    /* Find results from any of the provided models */
    protected static function findAnything(string $term, array $modelsAllowed): Builder
    {
        $query = self::baseQuery();
        
        foreach ($modelsAllowed as $modelClass) {
            $query->unionAll(
                $modelClass::find($term)
            );
        }
        
        return $query;
    }
}
