<?php

namespace App\Models\Inventory;

use App\Models\Master\Brand;
use App\Models\Master\RawMaterial;
use Illuminate\Database\Eloquent\Model;

class RawMaterialInventory extends Model
{
    protected $fillable = [
        'date',
        'raw_material_id',
        'brand_id',
        'in_qty',
        'out_qty',
        'balance',
        'rate',
        'value',
        'reference_type',
        'reference_id',
        'remarks',
    ];

    // optional relationships
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function referenceable()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

}
