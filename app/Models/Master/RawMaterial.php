<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    //
    protected $fillable = [
        'name',
        'unit_id',
        'raw_material_type_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // 🔗 Each raw material belongs to a unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // 🔗 Each raw material has a material type (like Yarn, Label, Box, etc.)
    public function type()
    {
        return $this->belongsTo(RawMaterialType::class, 'raw_material_type_id');
    }

    // public function getDisplayNameAttribute(): string
    // {

    // }

}

