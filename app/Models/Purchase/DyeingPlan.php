<?php

namespace App\Models\Purchase;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;

class DyeingPlan extends Model
{

    protected $fillable = [
        'plan_number',
        'dyer_id',
        'qc_status'
    ];

    // 🔗 Relationships
    public function items()
    {
        return $this->hasMany(DyeingPlanItem::class);
    }

    public function dyer()
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function generatePlanNumber(): string
    {
        // $datePart = now()->format('y-m-d-Hi'); // like PO-25-10-13-2235
        $datePart = now()->format('y-m-d-h:iA'); // like PO-25-10-13-10:35PM
        return 'DYE-' . $datePart;
    }

    protected static function booted()
    {
        static::creating(function ($plan) {
            if (!$plan->plan_number) {
                $plan->plan_number = self::generatePlanNumber();
            }
        });
    }
}
