<?php

namespace App\Models\Purchase;

use App\Models\Master\Unit;
use App\Models\Master\Brand;
use App\Models\Master\RawMaterial;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase\GoodsReceivedNote;

class GoodsReceivedNoteItem extends Model
{

    protected $fillable = [
        'grn_id',
        'raw_material_id',
        'brand_id',
        'unit_id',
        'quantity',
        'remarks',
    ];

    /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  */

    public function grn()
    {
        return $this->belongsTo(GoodsReceivedNote::class, 'grn_id');
    }

    // Optional: item belongs to a raw material
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Optional: item belongs to a brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Item has a unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    // Optional: display item summary
    public function getSummaryAttribute(): string
    {
        $material = $this->rawMaterial?->name ?? 'Unknown Material';
        $brand = $this->brand?->name ?? '';
        $unit = $this->rawMaterial?->unit?->symbol ?? '';
        return "{$material} {$brand} — {$this->quantity} {$unit}";
    }
}
