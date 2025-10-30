<?php

namespace App\Models\Purchase;

use App\Models\Master\Brand;
use Illuminate\Database\Eloquent\Model;

class StockTransferRecordItem extends Model
{
    protected $fillable = [
        'stock_transfer_record_id',
        'brand_id',
        'quantity',
        'remarks',
    ];

    /**
     * Each item belongs to one stock transfer record.
     */
    public function stockTransferRecord()
    {
        return $this->belongsTo(StockTransferRecord::class);
    }

    /**
     * Each item belongs to one brand.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
