<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Inventory\TwisterInventory;
use App\Models\Purchase\GoodsReceivedNote;
use App\Models\Inventory\RawMaterialInventory;

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
            // ->where('supplier_id', $do->supplier_id)
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
                'issue' => $do->quantity,
                'receive' => 0,
                'balance' => $newBalance,
                'remarks' => "Yarn sent to Twister via DO {$do->do_number}",
            ]);
        });
    }

    public static function renderHtml(array $balances)
    {

        // $html = '<div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">'; // fully responsive

        // foreach ($balances as $twister => $brands) {
        //     $totalBalance = array_sum($brands);

        //     $html .= '<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 hover:shadow-lg transition-shadow">';
        //     $html .= '<div class="flex justify-between items-center mb-3">';
        //     $html .= '<h3 class="text-lg font-semibold text-gray-700 dark:text-white">' . e($twister) . '</h3>';
        //     $html .= '<span class="text-sm font-medium text-gray-500 dark:text-gray-300">Total: ' . number_format($totalBalance, 2) . ' Kg</span>';
        //     $html .= '</div>';

        //     $html .= '<div class="space-y-1">';
        //     foreach ($brands as $brand => $balance) {
        //         $html .= '<div class="flex justify-between items-center text-sm text-gray-800 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition">';
        //         $html .= '<span class="font-medium">' . e($brand) . '</span>';
        //         $html .= '<span class="font-semibold text-indigo-600 dark:text-indigo-400">' . number_format($balance, 2) . ' Kg</span>';
        //         $html .= '</div>';
        //     }
        //     $html .= '</div>';

        //     $html .= '</div>'; // card
        // }

        // $html .= '</div>'; // grid wrapper

        // return $html;

        return view('partials.twister-inventory', compact('balances'))->render();
    }

    public static function getBalancesByTwisterAndBrand(): array
    {
        return TwisterInventory::select('twister_id', 'brand_id')
            ->selectRaw('SUM(issue) as total_issue, SUM(receive) as total_receive')
            ->groupBy('twister_id', 'brand_id')
            ->with(['twister:id,name', 'brand:id,name'])
            ->get()
            ->filter(fn($item) => ($item->total_issue - $item->total_receive) != 0)
            ->mapToGroups(fn($item) => [
                $item->twister->name => [
                    $item->brand->name => $item->total_issue - $item->total_receive
                ]
            ])
            ->mapWithKeys(fn($item, $key) => [$key => $item->collapse()])
            ->toArray();
    }

    public static function recordGrn(GoodsReceivedNote $grn)
    {
        if ($grn->status === 'verified') {
            $rawMaterial = $grn->rawMaterial;
            $isTwistedYarn = $rawMaterial?->type?->name === 'twisted_yarn';
            $isPurchasing = $grn->purchase_order_id !== null;

            if ($isTwistedYarn && !$isPurchasing) {
                // Normalize items: sum quantities by brand
                $normalizedItems = collect($grn->items)
                    ->groupBy('brand_id')
                    ->map(function ($items, $brandId) {
                        return [
                            'brand_id' => $brandId,
                            'quantity' => collect($items)->sum('quantity'),
                        ];
                    })->values();

                DB::transaction(function () use ($normalizedItems, $grn) {
                    foreach ($normalizedItems as $item) {
                        $brandId = $item['brand_id'];
                        $twisterId = $grn->supplier_id;
                        $quantity = $item['quantity'];

                        // Check if entry for this GRN item already exists
                        $existingEntry = TwisterInventory::where('goods_received_note_id', $grn->id)
                            ->where('twister_id', $twisterId)
                            ->where('brand_id', $brandId)
                            ->first();

                        if ($existingEntry) {
                            // Already recorded, skip or update if you want
                            continue; // skip duplicate creation
                        }

                        // Calculate current balance
                        $currentBalance = TwisterInventory::where('twister_id', $twisterId)
                            // ->where('brand_id', $brandId)
                            ->sum(DB::raw('issue - receive'));

                        $newBalance = $currentBalance - $quantity; // Yarn received → twister balance increases

                        // Create inventory entry
                        TwisterInventory::create([
                            'twister_id' => $twisterId,
                            'raw_material_id' => $grn->raw_material_id,
                            'brand_id' => $brandId,
                            'receive' => $quantity, // Receiving from twister
                            'issue' => 0,
                            'balance' => $newBalance,
                            'remarks' => "Yarn Received from Twister (Name: {$grn->supplier->name}) via GRN {$grn->grn_number}",
                            'goods_received_note_id' => $grn->id,
                        ]);

                        // 🧩 RawMaterialInventory update (mirror)
                        $currentMaterialBalance = RawMaterialInventory::where('raw_material_id', $grn->raw_material_id)
                            ->where('brand_id', $brandId)
                            ->sum(DB::raw('in_qty - out_qty'));

                        $newMaterialBalance = $currentMaterialBalance + $quantity;

                        RawMaterialInventory::create([
                            'date' => $grn->challan_date,
                            'raw_material_id' => $grn->raw_material_id,
                            'brand_id' => $brandId,
                            'in_qty' => $quantity,
                            'out_qty' => 0,
                            'balance' => $newMaterialBalance,
                            'reference_type' => GoodsReceivedNote::class,
                            'reference_id' => $grn->id,
                            'remarks' => "Received from Twister (Name: {$grn->supplier->name}) via GRN {$grn->grn_number}",
                        ]);
                    }
                });

            }
        }
    }
}
