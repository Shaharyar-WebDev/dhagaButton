<?php

namespace App\Models\Purchase;

use App\Models\Master\Brand;
use App\Models\Master\RawMaterial;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase\GoodsReceivedNote;

class GoodsReceivedNoteItem extends Model
{
    protected $fillable = [
        'goods_received_note_id',
        'raw_material_id',
        'brand_id',
        'quantity',
    ];

    /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  */

    // Belongs to a GRN
    public function grn()
    {
        return $this->belongsTo(GoodsReceivedNote::class, 'goods_received_note_id');
    }

    // Belongs to a raw material
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Optional brand relation for inventory/packing
    public function brand()
    {
        return $this->belongsTo(Brand::class);
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
