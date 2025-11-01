<?php

namespace App\Filament\Support\Helpers;

use App\Models\Master\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Helper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generateDocumentNumber(string $prefix, string $modelClass): string
    {
        // Get the latest ID safely (handle empty table)
        $latestId = $modelClass::max('id') ?? 0;
        $nextId = $latestId + 1;

        // Format parts
        $datePart = now()->format('d-m-Y');
        $sequencePart = str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Combine and return
        return strtoupper($prefix) . '-' . $datePart . '-' . $sequencePart;
    }

}

