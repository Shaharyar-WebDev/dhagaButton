<?php

namespace App\Services;

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
            'date' => now(),
            'remarks' => "Received {$totalQty} kg of {$po->rawMaterial->name} @ Rs {$rate}/{$po->rawMaterial->unit?->name} via GRN",
        ]);
    }

}
