<?php

namespace App\Models\Purchase;

use App\Models\Master\Brand;
use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use App\Models\Purchase\DeliveryOrder;
use App\Services\PurchaseOrderService;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Support\Helpers\Helper;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'raw_material_id',
        'ordered_quantity',
        'rate',
        'brand_id',
        'total_amount',
        'status',
        'remarks',
        'expected_delivery_date',
    ];


    // 🧾 Each purchase order belongs to one supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // 🧵 Each purchase order is for one raw material
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // 📦 Optional: link to GRNs (Goods Received Notes)
    // If you’re handling receipts separately, you can connect them like this later:


    public function verifiedDeliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class)->where('status', 'verified');
    }


    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function verifiedGoodsReceivedNotes()
    {
        return $this->hasMany(GoodsReceivedNote::class)
            ->where('status', 'verified');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    public function getVerifiedDeliveryOrdersSumQuantityAttribute()
    {
        return $this->verifiedDeliveryOrders()->sum('quantity');
    }

    public function getVerifiedGrnQuantityAttribute()
    {
        return $this->verifiedGoodsReceivedNotes()
            ->with('items')
            ->get()
            ->flatMap(fn($grn) => $grn->items)
            ->sum('quantity');
    }


    public static function generatePoNumber(): string
    {
        // $datePart = now()->format('y-m-d-Hi'); // like PO-25-10-13-2235
        $datePart = now()->format('d-m-y-h:i:sA');  // like PO-25-10-13-10:35PM
        return 'PO-' . $datePart;
    }


    // Calculate total on the fly if rate or quantity changes
    public function getCalculatedTotalAttribute(): float
    {
        return (float) ($this->ordered_quantity * ($this->rate ?? 0));
    }

    // Get short supplier + item summary for listings
    public function getSummaryAttribute(): string
    {
        $supplier = $this->supplier->name ?? 'Unknown Supplier';
        $material = $this->rawMaterial->name ?? 'Unknown Material';
        return "{$this->po_number} – {$supplier} ({$material})";
    }

    public function remainingQtyToReceive()
    {
        // total quantity ordered
        $orderedQty = $this->ordered_quantity ?? 0;

        if ($this->rawMaterial?->type?->name === 'twisted_yarn') {
            $receivedQty = $this->verifiedGoodsReceivedNotes()
                ->with('items')
                ->get()
                ->flatMap(fn($grn) => $grn->items)
                ->sum('quantity');
        } else {
            // sum of all quantities received in delivery orders
            $receivedQty = $this
                // ->deliveryOrders()
                ->verifiedDeliveryOrders()
                ->sum('quantity');
        }

        // remaining
        return $orderedQty - $receivedQty;
    }

    public function getReceivedQuantityAttribute()
    {
        return $this->deliveryOrders()->sum('quantity');
    }

    public function hasRemainingDoQty(): bool
    {
        $orderedQty = $this->ordered_quantity ?? 0;

        $receivedQty = $this->deliveryOrders()->sum('quantity');

        return $receivedQty < $orderedQty;
    }

    public function hasRemainingGrnQty(): bool
    {
        $orderedQty = $this->ordered_quantity ?? 0;

        $receivedQty = $this->verifiedGoodsReceivedNotes()
            ->with('items')
            ->get()
            ->flatMap(fn($grn) => $grn->items)
            ->sum('quantity');

        return $receivedQty < $orderedQty;
    }


    // public function canCreateGrn()
    // {
    //     dd (in_array($this->rawMaterial?->type, ['yarn', 'yarns']));
    //     // {
    //     //     return true;
    //     // }

    //     return false;
    // }

    public function canCreateDo()
    {
        return in_array($this->rawMaterial?->type->name, ['yarn', 'yarns']);
    }


    // Simple status color mapping for Filament badges if you want later
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'pending' => 'warning',
            'partially_received' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function getTitleAttributeName()
    {
        return $this->po_number;
    }

    public static function getStatusOptionAttr()
    {
        return [
            'options' => [
                'draft' => 'Draft',
                'pending' => 'Pending',
                'partially_received' => 'Partially Received',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ],
            'colors' => [
                'draft' => 'gray',
                'pending' => 'warning',
                'partially_received' => 'info',
                'completed' => 'success',
                'cancelled' => 'danger',
            ],
            'icons' => [
                'draft' => 'heroicon-o-pencil-square',
                'pending' => 'heroicon-o-check-badge',
                'partially_received' => 'heroicon-o-clock',
                'completed' => 'heroicon-o-check-circle',
                'cancelled' => 'heroicon-o-x-circle',
            ]
        ];
    }

    protected static function booted()
    {
        static::creating(function ($po) {
            if (!$po->po_number) {
                $po->po_number = Helper::generateDocumentNumber('PO', PurchaseOrder::class);
            }
        });

        static::saved(function ($po) {
            PurchaseOrderService::handlePoStatusForYarn($po);
        });
    }
}
