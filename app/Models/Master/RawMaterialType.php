<?php

namespace App\Models\Master;

use App\Models\Traits\HasCachedOptions;
use Illuminate\Database\Eloquent\Model;

class RawMaterialType extends Model
{
    //
    use HasCachedOptions;

    protected $fillable = [
        'name'
    ];

    public static $optionsLabel = null;
}
