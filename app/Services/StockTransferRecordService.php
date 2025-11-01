<?php

namespace App\Services;

use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\DyerInventory;
use App\Models\Inventory\TwisterInventory;
use App\Models\Inventory\RawMaterialInventory;
use App\Models\Purchase\StockTransferRecord;

class StockTransferRecordService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function recordStr(StockTransferRecord $str)
    {
        if ($str->status === 'verified' && !$str->locked) {

            DB::transaction(function () use ($str) {
                foreach ($str->items as $item) {
                    self::handleInventory($str, $item);
                }
            });
            $str->lock();
        }
    }

    public static function handleInventory(StockTransferRecord $str, $item)
    {
        $qty = $item['quantity'];
        $brandId = $item['brand_id'];
        $rawMaterialId = $str->raw_material_id;
        $from = $str->from_supplier_id;
        $to = $str->to_supplier_id;
        // 🏭 CASE 1: Transferred from factory inventory → supplier
        if (is_null($from)) {
            $currentBalance = RawMaterialInventory::where('raw_material_id', $rawMaterialId)
                ->where('brand_id', $brandId)
                ->sum(DB::raw('in_qty - out_qty'));

            $newBalance = $currentBalance - $qty;

            RawMaterialInventory::create([
                'date' => $str->challan_date,
                'raw_material_id' => $rawMaterialId,
                'brand_id' => $brandId,
                'in_qty' => 0,
                'out_qty' => $qty,
                'balance' => $newBalance,
                'reference_type' => get_class($str),
                'reference_id' => $str->id,
                'remarks' => "Transferred to Supplier (Name: {$str->toSupplier?->name}) via STR #{$str->str_number}",
            ]);
        }

        // 🧵 CASE 2: Transferred from a Twister’s inventory → supplier
        else {
            // 💡 No brand filter here because Twister inventory isn’t brand-specific
            $currentBalance = TwisterInventory::where('twister_id', $from)
                ->sum(DB::raw('issue - receive'));

            $newBalance = $currentBalance - $qty;

            TwisterInventory::create([
                'twister_id' => $from,
                'supplier_id' => $from,
                'raw_material_id' => $rawMaterialId,
                'stock_transfer_record_id' => $str->id,
                'brand_id' => $brandId,
                'receive' => $qty,
                'dyer_id' => $to,
                'issue' => 0,
                'balance' => $newBalance,
                'date' => $str->challan_date,
                'remarks' => "Transferred to Supplier (ID: {$str->toSupplier?->name}) via STR #{$str->str_number}",
            ]);
        }

        $rawMaterial = RawMaterial::with('type')->find($rawMaterialId);

        if ($rawMaterial) {
            if ($rawMaterial->type->name === 'twisted_yarn') {
                // 🧵 Current Dyer balance before new receipt (not brand-specific)
                $currentBalance = DyerInventory::where('raw_material_id', $rawMaterialId)
                    ->where('dyer_id', $to)
                    ->sum(DB::raw('issue - receive'));

                $newBalance = $currentBalance + $qty;

                DyerInventory::create([
                    'from_supplier_id' => $from,
                    'dyer_id' => $to,
                    'stock_transfer_record_id' => $str->id,
                    'raw_material_id' => $rawMaterialId,
                    'brand_id' => $brandId, // optional or leave if column exists
                    'issue' => $qty,       // Yarn received by dyer
                    'receive' => 0,
                    'balance' => $newBalance,
                    'date' => $str->challan_date,
                    'remarks' => "Received Twisted Yarn from " . ($str->fromSupplier?->name ?? 'Factory') . " via STR #{$str->str_number}",
                ]);
            } elseif ($rawMaterial->type->name === 'boxes') {
                // Handle boxes logic
            }
        }
    }
}
