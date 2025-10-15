<?php

namespace App\Models\Purchase;

use App\Models\Master\Brand;
use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use App\Models\Purchase\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'do_number',
        'delivery_order_reference',
        'delivery_order_images',
        'purchase_order_id',
        'raw_material_id',
        'challan_reference',
        'supplier_id',
        'twister_id',
        'brand_id',
        'quantity',
    ];

    protected $casts = [
        'delivery_order_images' => 'array',
    ];

    // Supplier from whom yarn was purchased
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Twister (job worker) to whom yarn is delivered
    public function twister()
    {
        return $this->belongsTo(Supplier::class, 'twister_id');
    }

    public function pendingPurchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')
            ->whereIn('status', ['pending', 'partially_received']);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Brand of yarn or material being delivered
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public static function generatePoNumber(): string
    {
        // $datePart = now()->format('d-m-y-Hi'); // like PO-25-10-13-2235
        $datePart = now()->format('d-m-y-h:i:sA');  // like PO-25-10-13-10:35PM
        return 'DO-' . $datePart;
    }

    protected static function booted()
    {
        static::creating(function ($do) {
            if (!$do->do_number) {
                $do->do_number = self::generatePoNumber();
            }
        });
    }

}
