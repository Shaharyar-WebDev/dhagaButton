<?php

namespace App\Models\Purchase;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase\GoodsReceivedNoteItem;

class GoodsReceivedNote extends Model
{
    protected $fillable = [
        'grn_number',
        'purchase_order_id',
        'supplier_id',
        'reference_number',
        'received_date',
        'remarks',
    ];


    // Each GRN belongs to a supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Each GRN may be linked to a purchase order
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
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
        $po = $this->purchaseOrder?->po_number ?? 'No PO';
        $supplier = $this->supplier?->name ?? 'Unknown Supplier';
        return "{$this->grn_number} — {$supplier} ({$po})";
    }

    // Optional: compute total received quantity across items
    public function getTotalReceivedAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->received_quantity);
    }

    protected static function booted()
    {
        static::creating(function ($grn) {
            if (!$grn->grn_number) {
                $grn->grn_number = self::generateGrnNumber();
            }
        });
    }
}
