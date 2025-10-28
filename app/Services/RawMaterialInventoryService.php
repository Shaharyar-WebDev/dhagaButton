<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase\GoodsReceivedNote;
use App\Models\Inventory\RawMaterialInventory;

class RawMaterialInventoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public static function calculateBalance($rawMaterialId, $brandId, $inQty)
    {
        $currentBalance = RawMaterialInventory::where('raw_material_id', $rawMaterialId)
            ->where('brand_id', $brandId)
            ->sum(DB::raw('in_qty - out_qty'));

        return $currentBalance + $inQty;
    }

    public static function recordGrn(GoodsReceivedNote $grn)
    {
        if ($grn->status === 'verified' && !$grn->locked) {
            foreach ($grn->items as $item) {
                RawMaterialInventory::create([
                    'date' => $grn->challan_date,
                    'raw_material_id' => $grn->raw_material_id,
                    'brand_id' => $item->brand_id,
                    'in_qty' => $item->quantity,
                    'balance' => self::calculateBalance($grn->raw_material_id, $item->brand_id, $item->quantity),
                    // 'rate' => $item->rate,
                    // 'value' => $item->quantity * $item->rate,
                    'reference_type' => GoodsReceivedNote::class,
                    'reference_id' => $grn->id,
                    'remarks' => $item->remarks,
                ]);
            }
            $grn->lock();
        }
        PurchaseOrderService::updateStatusFromGrn($grn);
    }

}
