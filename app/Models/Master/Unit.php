<?php

namespace App\Models\Master;

use App\Models\Traits\HasCachedOptions;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasCachedOptions;
    protected $fillable = [
        'name',
        'symbol',
        'conversion_operator',
        'conversion_value',
        'base_unit_id',
    ];

    public static $optionsLabel = null;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // 🔁 A unit can belong to another base unit
    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    // 🔁 A base unit can have many derived units
    public function derivedUnits()
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    // Optional: display full unit info e.g. "Kilogram (kg)"
    public function getDisplayNameAttribute(): string
    {
        return $this->symbol
            ? "{$this->name} ({$this->symbol})"
            : $this->name;
    }

}
