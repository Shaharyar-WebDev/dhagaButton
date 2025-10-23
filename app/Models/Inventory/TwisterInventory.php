<?php

namespace App\Models\Inventory;

use App\Models\Master\Brand;
use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Purchase\GoodsReceivedNote;
use App\Models\Purchase\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;

class TwisterInventory extends Model
{
    protected $fillable = [
        'supplier_id',
        'twister_id',
        'delivery_order_id',
        'purchase_order_id',
        'goods_received_note_id',
        'raw_material_id',
        'brand_id',
        'debit',
        'credit',
        'balance',
        'remarks',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Supplier from whom yarn was purchased
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function grn()
    {
        return $this->belongsTo(GoodsReceivedNote::class, 'goods_received_note_id');
    }

    // Twister (job worker) to whom yarn is sent or received from
    public function twister()
    {
        return $this->belongsTo(Supplier::class, 'twister_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Delivery Order linked to this movement (optional)
    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    // Purchase order (original source of yarn)
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    // Brand of yarn being tracked
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
