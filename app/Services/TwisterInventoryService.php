<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Inventory\TwisterInventory;

class TwisterInventoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function recordDeliveryOrder(DeliveryOrder $do): void
    {
        if ($do->status !== 'verified') {
            return;
        }

        // If ledger already exists, skip
        if ($do->twisterInventory()->exists()) {
            return;
        }

        // Get last balance for same Twister + Supplier
        $lastBalance = TwisterInventory::where('twister_id', $do->twister_id)
            ->where('supplier_id', $do->supplier_id)
            ->orderByDesc('id')
            ->value('balance') ?? 0;

        $newBalance = $lastBalance + $do->quantity;

        DB::transaction(function () use ($do, $newBalance) {
            // Create ledger entry
            $do->twisterInventory()->create([
                'supplier_id' => $do->supplier_id,
                'twister_id' => $do->twister_id,
                'purchase_order_id' => $do->purchase_order_id,
                'raw_material_id' => $do->raw_material_id,
                'brand_id' => $do->brand_id,
                'debit' => 0,
                'credit' => $do->quantity,
                'balance' => $newBalance,
                'remarks' => "Yarn sent to Twister via DO {$do->do_number}",
            ]);
        });
    }

}
