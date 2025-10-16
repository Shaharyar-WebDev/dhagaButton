<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase\DeliveryOrder;

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

        $ordered = $purchaseOrder->ordered_quantity;
        $received = $purchaseOrder->verifiedDeliveryOrders()->sum('quantity');


        DB::transaction(function () use ($received, $ordered, $purchaseOrder) {
            if ($received >= $ordered) {
                $purchaseOrder->update(['status' => 'completed']);
            } elseif ($received > 0) {
                $purchaseOrder->update(['status' => 'partially_received']);
            }
        });
    }

}
