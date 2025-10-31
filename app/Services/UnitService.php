<?php

namespace App\Services;

use App\Models\Master\Unit;

class UnitService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getUnits()
    {
        $kg = Unit::where('name', 'Kilograms')->first();
        $bundle = Unit::where('name', 'Bundle')->first();
        $bag = Unit::where('name', 'Bag')->first();
        $units = collect([$kg, $bundle, $bag])->filter(); // skip nulls
        return $units;
    }

    public static function formatUnitConversions(float $qty, Unit $baseUnit): string
    {
        // Fetch all units convertible from this base
        $relatedUnits = Unit::all()->filter(
            fn($u) =>
            $u->getRootUnit()->id === $baseUnit->getRootUnit()->id
        );

        $convertedStrings = [];

        foreach ($relatedUnits as $unit) {
            if ($unit->id === $baseUnit->id)
                continue;

            $converted = $baseUnit->convertTo($unit, $qty);

            if ($converted !== null) {
                $convertedStrings[] = number_format($converted, 2) . ' ' . $unit->symbol;
            }
        }

        $main = number_format($qty, 2) . ' ' . $baseUnit->symbol;
        $extra = $convertedStrings ? ' (' . implode(', ', $convertedStrings) . ')' : '';

        return $main . $extra;
    }
}
