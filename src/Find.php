<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Find
{
    /* Perform a search for a term */
    public static function find(string $term, string $type): Builder
    {
        $anythingKey = config('laravel-find.anything-key');
        $modelsAllowed = self::types(false);

        if (array_key_exists($type, $modelsAllowed) === false) {
            throw new AuthorizationException("You do not have permission to find $type");
        }

        return $type === $anythingKey
            ? self::findAnything($term, $modelsAllowed, $anythingKey)
            : $modelsAllowed[$type]::find($term);
    }
    
    /* Which models the current User can find */
    public static function types(bool $labels = true): array
    {
        $anythingKey = config('laravel-find.anything-key');
        $models = config('laravel-find.models');
        $findable = [];
        $user = Auth::user();
        
        if ($anythingKey !== false) {
            $findable[$anythingKey] = config('laravel-find.anything-label');
        }
        
        foreach ($models as $type => $modelClass) {
            if ($modelClass::canBeFoundBy($user) === true) {
                $findable[$type] = $labels === false
                    ? $modelClass
                    : $modelClass::findTypeLabel();
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
                DB::raw('"Description" as description'),
                DB::raw('"Link" as link'),
            ])
            ->whereRaw('1 = 2');
    }
    
    /* Find results from any of the provided models */
    protected static function findAnything(string $term, array $modelsAllowed, string $anythingKey): Builder
    {
        $query = self::baseQuery();
        unset($modelsAllowed[$anythingKey]);
        
        foreach ($modelsAllowed as $modelClass) {
            $query->unionAll(
                $modelClass::find($term)
            );
        }
        
        return $query;
    }
}
