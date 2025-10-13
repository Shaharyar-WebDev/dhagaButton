<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait HasCachedOptions
{
    // public static $optionsLabel = null;

    public static function cachedOptions(string|null $optionsLabel = null): array
    {
        $className = class_basename(self::class);
        $cacheKey = "$className:options";
        // self::refreshOptionsCache();

        return Cache::rememberForever($cacheKey, function () {

            $labelColumn = self::$optionsLabel ?? $optionsLabel ?? 'name';
            $optionRelations = static::$optionRelation ?? null;
            $query = self::query();

            if (!empty($optionRelations)) {
                $options = $query->with($optionRelations)->get()->pluck($labelColumn, 'id')->toArray();
            } else {
                $options = $query->pluck($labelColumn, 'id')->toArray();
            }

            return $options;
        });
    }

    public static function refreshOptionsCache(): void
    {
        $cacheKey = class_basename(self::class) . ':options';
        Cache::forget($cacheKey);
    }

    protected static function bootHasCachedOptions()
    {
        static::saved(function ($po) {
            self::refreshOptionsCache();
        });
        static::deleted(function ($model) {
            self::refreshOptionsCache();
        });
    }
}
