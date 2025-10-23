<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase\DeliveryOrder;
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

}
