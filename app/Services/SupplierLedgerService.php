<?php

namespace App\Services;

use App\Models\Purchase\DeliveryOrder;
use App\Models\Accounting\SupplierLedger;
use App\Models\Purchase\GoodsReceivedNote;

class SupplierLedgerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function calculateBalance($supplierId, $newCredit = 0, $newDebit = 0)
    {
        $previousBalance = SupplierLedger::where('supplier_id', $supplierId)
            ->latest('id')
            ->value('balance') ?? 0;

        return $previousBalance + $newCredit - $newDebit;
    }

    public static function recordGrn(GoodsReceivedNote $grn)
    {
        $po = $grn->purchaseOrder;

        $supplier = $po->supplier;
        $rate = $po->rate ?? 0;

        // Sum of all GRN item quantities
        $totalQty = $grn->items->sum('quantity');
        $totalAmount = $totalQty * $rate;

        SupplierLedger::create([
            'supplier_id' => $supplier->id,
            'source_type' => get_class($grn),
            'source_id' => $grn->id,
            'transaction_type' => 'GRN',
            'debit' => 0,
            'credit' => $totalAmount,
            'balance' => self::calculateBalance($supplier->id, $totalAmount),
            'reference_no' => $grn->challan_no,
            'date' => $grn->challan_date,
            'remarks' => "Received {$totalQty} kg of {$po->rawMaterial->name} {$po->brand?->name} @ Rs {$rate}/{$po->rawMaterial->unit?->name} via GRN",
        ]);
    }

    public static function recordDeliveryOrder(DeliveryOrder $do)
    {
        if ($do->status !== 'verified') {
            return;
        }

        // Avoid duplicates
        $exists = SupplierLedger::where('source_type', DeliveryOrder::class)
            ->where('source_id', $do->id)
            ->exists();

        if ($exists) {
            return;
        }

        $po = $do->purchaseOrder;
        $supplier = $do->supplier;
        $rate = $po->rate ?? 0;
        $amount = $do->quantity * $rate;

        SupplierLedger::create([
            'supplier_id' => $supplier->id,
            'source_type' => DeliveryOrder::class,
            'source_id' => $do->id,
            'transaction_type' => 'DO',
            'debit' => 0, // no money going out
            'credit' => $amount, // supplier delivered goods
            'balance' => self::calculateBalance($supplier->id, $amount),
            'reference_no' => $do->challan_reference,
            'date' => $do->challan_date,
            'remarks' => "Delivery of {$do->quantity} bags ({$po->rawMaterial->name}) {$po->brand?->name} Brand from {$supplier->name} via DO {$do->do_number}",
        ]);
    }

}
