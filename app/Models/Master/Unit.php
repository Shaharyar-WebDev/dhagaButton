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

    public function convertTo(Unit $targetUnit, float $qty): float|null
    {
        // 1️⃣ If same unit, just return qty
        if ($this->id === $targetUnit->id) {
            return $qty;
        }

        // Find top-most base for both units
        $thisRoot = $this->getRootUnit();
        $targetRoot = $targetUnit->getRootUnit();

        // ⚠️ If roots are different, they’re not convertible (e.g., pc vs kg)
        if ($thisRoot->id !== $targetRoot->id) {
            return null; // or maybe return 0 or null, your choice
        }

        // Convert through the shared chain
        $qtyInBase = $this->toBase($qty);
        return $targetUnit->fromBase($qtyInBase);
    }

    public function toBase(float $qty): float
    {
        // If this unit has no base unit, it *is* the base
        if (!$this->base_unit_id) {
            return $qty;
        }

        $operator = $this->conversion_operator;
        $value = $this->conversion_value;

        $baseUnit = $this->baseUnit;

        // Apply conversion
        $converted = $operator === '*'
            ? $qty * $value
            : $qty / $value;

        // Continue up the chain if base unit also has a base
        return $baseUnit->toBase($converted);
    }

    public function fromBase(float $qty): float
    {
        // If this unit has no base, return as is
        if (!$this->base_unit_id) {
            return $qty;
        }

        $operator = $this->conversion_operator;
        $value = $this->conversion_value;

        // Apply reverse conversion
        $converted = $operator === '*'
            ? $qty / $value
            : $qty * $value;

        $baseUnit = $this->baseUnit;

        // If the base unit also has a base, convert down the chain
        return $baseUnit->fromBase($converted);
    }

    public function getRootUnit(): Unit
    {
        $unit = $this;
        while ($unit->base_unit_id) {
            $unit = $unit->baseUnit;
        }
        return $unit;
    }

}
