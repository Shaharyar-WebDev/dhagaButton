<?php

namespace App\Models\Purchase;

use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\SupplierLedger;
use App\Services\TwisterInventoryService;
use App\Models\Inventory\TwisterInventory;
use App\Services\RawMaterialInventoryService;
use App\Models\Inventory\RawMaterialInventory;
use App\Models\Purchase\GoodsReceivedNoteItem;

class GoodsReceivedNote extends Model
{
    protected $fillable = [
        'grn_number',
        'supplier_id',
        'purchase_order_id',
        'challan_no',
        'challan_date',
        'raw_material_id',
        'remarks',
        'attachments',
        'locked'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];


    // A GRN belongs to a supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // A GRN belongs to a purchase order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // A GRN belongs to a delivery order
    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    // Optional: link to received items (if you have a GRN items table)
    public function items()
    {
        return $this->hasMany(GoodsReceivedNoteItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors / Helpers
    |--------------------------------------------------------------------------
    */

    public static function generateGrnNumber(): string
    {
        // $datePart = now()->format('y-m-d-Hi'); // like PO-25-10-13-2235
        $datePart = now()->format('y-m-d-h:iA'); // like PO-25-10-13-10:35PM
        return 'GRN-' . $datePart;
    }

    // Optional: display GRN summary for tables or dropdowns
    public function getSummaryAttribute(): string
    {
        $po = $this->purchaseOrder?->po_number ?? $this->purchaseOrder?->do_number ?? 'N\A';
        $supplier = $this->supplier?->name ?? 'Unknown Supplier';
        return "{$this->grn_number} — {$supplier} ({$po})";
    }

    // Optional: compute total received quantity across items
    public function getTotalReceivedAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->received_quantity);
    }

    public function lock()
    {
        if (!$this->locked) {
            $this->updateQuietly(['locked' => true]);
        }
    }

    protected static function booted()
    {
        static::creating(function ($grn) {
            if (!$grn->grn_number) {
                $grn->grn_number = self::generateGrnNumber();
            }
        });

        static::saved(function ($grn) {
            if ($grn->purchase_order_id) {
                RawMaterialInventoryService::recordGrn($grn);
            } else {
                TwisterInventoryService::recordGrn($grn);
            }
        });

        static::deleted(function ($grn) {
            SupplierLedger::where('source_type', get_class($grn))
                ->where('source_id', $grn->id)
                ->delete();
        });

    }
}
