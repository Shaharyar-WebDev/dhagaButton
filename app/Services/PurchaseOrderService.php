<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Purchase\GoodsReceivedNote;
use App\Models\Purchase\PurchaseOrder;

class PurchaseOrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function updateStatusFromDeliveryOrder(DeliveryOrder $deliveryOrder)
    {
        $purchaseOrder = $deliveryOrder->purchaseOrder;

        if (!$purchaseOrder)
            return;


        self::handlePoStatusForYarn($purchaseOrder);

    }

    public static function updateStatusFromGrn(GoodsReceivedNote $grn)
    {
        $purchaseOrder = $grn->purchaseOrder;

        if (!$purchaseOrder)
            return;


        self::handlePoStatusForNonYarn($purchaseOrder);

    }

    public static function handlePoStatusForYarn(PurchaseOrder $purchaseOrder)
    {
        if (in_array($purchaseOrder->rawMaterial->type->name, ['yarns', 'yarn'])) {
            $ordered = $purchaseOrder->ordered_quantity;
            $received = $purchaseOrder->verifiedDeliveryOrders()->sum('quantity');

            DB::transaction(function () use ($received, $ordered, $purchaseOrder) {
                if ($received >= $ordered) {
                    $purchaseOrder->updateQuietly(['status' => 'completed']);
                } elseif ($received > 0) {
                    $purchaseOrder->updateQuietly(['status' => 'partially_received']);
                } else {
                    $purchaseOrder->updateQuietly(['status' => 'pending']);
                }
            });
            // dd($purchaseOrder, $ordered, $received);

        }
    }

    public static function handlePoStatusForNonYarn(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->rawMaterial->type->name, ['yarns', 'yarn'])) {
            $ordered = $purchaseOrder->ordered_quantity;
            $received = $purchaseOrder->verifiedGoodsReceivedNotes()
                ->with('items')
                ->get()
                ->flatMap(fn($grn) => $grn->items)
                ->sum('quantity');

            DB::transaction(function () use ($received, $ordered, $purchaseOrder) {
                if ($received >= $ordered) {
                    $purchaseOrder->updateQuietly(['status' => 'completed']);
                } elseif ($received > 0) {
                    $purchaseOrder->updateQuietly(['status' => 'partially_received']);
                } else {
                    $purchaseOrder->updateQuietly(['status' => 'pending']);
                }
            });
            // dd($purchaseOrder, $ordered, $received);

        }
    }

}
