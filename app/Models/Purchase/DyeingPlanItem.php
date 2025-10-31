<?php

namespace App\Models\Purchase;

use App\Models\Master\Unit;
use App\Models\Master\Shade;
use App\Models\Purchase\DyeingPlan;
use Illuminate\Database\Eloquent\Model;

class DyeingPlanItem extends Model
{
    protected $fillable = [
        'dyeing_plan_id',
        'shade_id',
        'quantity',
        'unit_id',
        'date',
        'qc_status',
        'qc_date'
    ];

    // 🔗 Relationships
    public function dyeingPlan()
    {
        return $this->belongsTo(DyeingPlan::class);
    }

    public function shade()
    {
        return $this->belongsTo(Shade::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
