<?php

namespace App\Models\Inventory;

use App\Models\Master\Brand;
use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase\StockTransferRecord;

class DyerInventory extends Model
{
    protected $fillable = [
        'from_supplier_id',
        'dyer_id',
        'stock_transfer_record_id',
        'raw_material_id',
        'brand_id',
        'issue',
        'receive',
        'date',
        'balance',
        'remarks',
    ];

    //  |--------------------------------------------------------------------------
    // | Relationships
    // |--------------------------------------------------------------------------
    // */

    // Each inventory belongs to a Twister (Supplier)
    public function fromSupplier()
    {
        return $this->belongsTo(Supplier::class, 'from_supplier_id');
    }

    // Each inventory may belong to a Dyer (Supplier)
    public function dyer()
    {
        return $this->belongsTo(Supplier::class, 'dyer_id');
    }

    // Optional: related stock transfer record
    public function str()
    {
        return $this->belongsTo(StockTransferRecord::class, 'stock_transfer_record_id');
    }

    // Related raw material
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Related brand (optional)
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
