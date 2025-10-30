<?php

namespace App\Models\Accounting;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    protected $fillable = [
        'supplier_id',
        'source_type',
        'source_id',
        'transaction_type',
        'debit',
        'credit',
        'balance',
        'reference_no',
        'date',
        'remarks',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

}
