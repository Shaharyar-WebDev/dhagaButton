<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Schemas;

use App\Models\Master\Brand;
use Filament\Schemas\Schema;
use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\View;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Purchase\PurchaseOrder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use App\Services\TwisterInventoryService;
use Filament\Forms\Components\DatePicker;
use App\Models\Inventory\TwisterInventory;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class GoodsReceivedNoteForm
{
    public static function updateFields($state, $set)
    {
        if (!$state) {
            if (request()->has('purchase_order_id')) {
                $reqPoId = request()->query('purchase_order_id');
                $set('purchase_order_id', $reqPoId);
                $set('request_po_id', $reqPoId);
                $po = PurchaseOrder::find($reqPoId);
                // $poId = $reqPoId;
                $set('raw_material_id', $po->raw_material_id);
                $set('supplier_id', $po->supplier_id);


            } else if (request()->has('delivery_order_id')) {
                $reqDoId = request()->query('delivery_order_id');
                $set('delivery_order_id', $reqDoId);
                $set('request_do_id', $reqDoId);
                // $doId = $reqDoId;
            } else {
                return;
            }
        }

        // if ($reqPoId) {
        //     $po = PurchaseOrder::findOrFail($reqPoId);

        //     if ($po) {
        //         $set('raw_material_id', $po->raw_material_id);
        //         $set('brand_id', $po->brand_id);
        //         $set('supplier_id', $po->supplier_id);
        //         $set('unit_name', $po->rawMaterial?->unit?->symbol);
        //         $set('max_quantity', $po->ordered_quantity);

        //         $doQty = $po->deliveryOrders()->sum('quantity') ?? 0;
        //         $poQty = $po->ordered_quantity ?? 0;

        //         $set('available_quantity', $poQty - $doQty);
        //     }
        // }

        // if ($reqDoId) {
        //     $po = DeliveryOrder::findOrFail($reqPoId);
        // }




    }

    public static function getForm()
    {
        function checkIfIsPurchasing(callable $set)
        {
            if (request()->has('is_purchasing')) {
                $set('is_purchasing', true);
            }
            if (request()->has('raw_material_id')) {
                $set('raw_material_id', request('raw_material_id'));
            }
        }

        return [
            Section::make()->columnSpanFull()->columns(2)->schema([
                Grid::make(3)->columnSpanFull()->schema([

                    Select::make('raw_material_id')
                        ->label('Raw Material')
                        ->relationship('rawMaterial', 'name')
                        ->afterStateHydrated(fn($set) => checkIfIsPurchasing($set))
                        ->afterStateUpdated(function ($state, $set, $get) {

                            if (!$state)
                                return;
                            $rawMaterial = RawMaterial::find($state);

                            if (!$rawMaterial)
                                return;

                            if ($rawMaterial->type->name === 'twisted_yarn') {

                            };

                            $set('unit_name', $rawMaterial->unit?->symbol);
                        })
                        ->reactive()
                        ->searchable()
                        ->disabled(fn($get) => $get('request_po_id'))
                        ->dehydrated()
                        ->preload()
                        ->required(),

                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->relationship('supplier', 'name')
                        ->preload()
                        ->reactive()
                        ->disabled(fn($get) => $get('request_po_id'))
                        ->searchable()
                        ->dehydrated()
                        ->required(),

                    Select::make('purchase_order_id')
                        ->label('Purchase Order')
                        // ->disabled(fn($get) => $get('request_do_id'))
                        ->disabled(true)
                        ->afterStateHydrated(fn($state, $set) => self::updateFields($state, $set))
                        ->afterStateUpdated(fn($state, $set) => self::updateFields($state, $set))
                        ->relationship('purchaseOrder', 'po_number')
                        ->preload()
                        ->dehydrated()
                        ->searchable()
                        ->nullable(),

                ]),

                TextInput::make('challan_no')
                    ->label('Challan Number')
                    ->nullable()
                    ->maxLength(255),

                DatePicker::make('challan_date')
                    ->label('Challan Date')
                    ->required(),

                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('twisted_inventory_info')
                            ->label('Twisted Yarn Inventory')
                            ->html()
                            ->state(function ($get) {
                                $balances = TwisterInventoryService::getBalancesByTwisterAndBrand();
                                return TwisterInventoryService::renderHtml($balances);
                            })
                    ])
                    ->visible(function ($get) {

                        if ($get('raw_material_id') && RawMaterial::find($get('raw_material_id'))?->type?->name == 'twisted_yarn') {

                            if ($get('purchase_order_id')) {
                                return false;
                            } else {
                                return true;
                            }

                        };

                        return false;
                    }),

                Repeater::make('items')
                    ->relationship()
                    ->defaultItems(0)
                    ->minItems(1)
                    ->statePath('items')
                    ->label('Goods Received Note Items')
                    ->addActionLabel('Add Item')
                    ->schema([
                        Select::make('brand_id')
                            ->label('Brand')
                            ->reactive()
                            ->options(function ($get) {
                                $purchaseOrderId = $get('../../purchase_order_id');

                                $purchaseOrderId = $get('../../purchase_order_id');

                                if (!$purchaseOrderId) {
                                    return Brand::pluck('name', 'id');
                                }

                                $po = PurchaseOrder::find($purchaseOrderId);

                                if (!$po || !$po->brand_id) {
                                    return [];
                                }

                                return Brand::where('id', $po->brand_id)->pluck('name', 'id');
                            })
                            // ->relationship('brand', 'name')
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->suffix(fn($get) => $get('../../unit_name'))
                            ->rules(function ($get) {

                                $isPurchasing = $get('../../purchase_order_id');

                                $isUpdating = filled($get('../../id'));

                                if ($isUpdating) {
                                    return []; // skip validation on edit
                                }

                                if ($isPurchasing) {
                                    return [
                                        function ($attribute, $value, $fail) use ($get) {
                                            $purchaseOrderId = $get('../../purchase_order_id');
                                            $po = PurchaseOrder::find($purchaseOrderId);

                                            if (!$po) {
                                                return;
                                            }

                                            $orderedQty = $po->ordered_quantity ?? 0;

                                            // Total quantity already received (verified GRNs)
                                            $alreadyReceivedQty = $po->verifiedGoodsReceivedNotes()
                                                ->withSum('items', 'quantity')
                                                ->get()
                                                ->pluck('items_sum_quantity')
                                                ->sum();

                                            $remainingQty = max($orderedQty - $alreadyReceivedQty, 0);

                                            $unit = $po->rawMaterial?->unit?->symbol ?? '';

                                            // Now also sum up quantities being entered in *this repeater*
                                            $repeaterItems = $get('../../items') ?? [];
                                            $totalEnteredQty = 0;
                                            foreach ($repeaterItems as $item) {
                                                $totalEnteredQty += $item['quantity'] ?? 0;
                                            }

                                            if ($totalEnteredQty > $remainingQty) {
                                                $fail("Total entered quantity ({$totalEnteredQty} {$unit}) exceeds remaining order quantity ({$remainingQty} {$unit}).");
                                            }
                                        }
                                    ];
                                } else {
                                    return [
                                        function ($attribute, $value, $fail) use ($get) {
                                            $brandId = $get('brand_id');
                                            $supplierId = $get('../../supplier_id');

                                            if (!$brandId || !$supplierId)
                                                return;

                                            // Get total available balance for the brand
                                            $balance = TwisterInventory::where('twister_id', $supplierId)
                                                ->where('brand_id', $brandId)
                                                ->sum(DB::raw('issue - receive'));

                                            // Sum all quantities entered in repeater for this brand
                                            $repeaterItems = $get('../../items');
                                            $totalQuantity = 0;
                                            foreach ($repeaterItems as $item) {
                                                if (isset($item['brand_id']) && $item['brand_id'] == $brandId) {
                                                    $totalQuantity += $item['quantity'] ?? 0;
                                                }
                                            }

                                            if ($totalQuantity > $balance) {
                                                $fail("Total quantity for this brand exceeds available balance ({$balance} Kg).");
                                            }
                                        }
                                    ];
                                }
                            })
                            ->helperText(function ($get) {
                                $isPurchasing = $get('../../purchase_order_id');

                                if ($isPurchasing) {
                                    $po = PurchaseOrder::find($isPurchasing);

                                    if ($po) {
                                        $orderedQty = $po->ordered_quantity ?? 0;
                                        $unit = $po->rawMaterial?->unit?->symbol ?? '';

                                        $alreadyReceivedQty = $po->verifiedGoodsReceivedNotes()
                                            ->withSum('items', 'quantity') // efficient sum in DB
                                            ->get()
                                            ->pluck('items_sum_quantity')
                                            ->sum();

                                        $remainingQty = max($orderedQty - $alreadyReceivedQty, 0);

                                        // Format only for display
                                        $formattedRemaining = number_format($remainingQty, 3);

                                        return "Quantity To Receive: {$formattedRemaining} {$unit}";
                                    }
                                } else {
                                    $brandId = $get('brand_id');
                                    $supplierId = $get('../../supplier_id');

                                    if (!$brandId || !$supplierId)
                                        return '';

                                    $balance = TwisterInventory::where('twister_id', $supplierId)
                                        ->where('brand_id', $brandId)
                                        ->sum(DB::raw('issue - receive'));

                                    return "Available: {$balance} Kg";
                                }
                            })
                            ->minValue(0)
                            ->step(0.01)
                            ->required(),

                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->rows(2)
                            ->maxLength(255),
                    ])
                    ->afterStateUpdated(function () {
                    })
                    ->columnSpanFull()
                    ->columns(3),

                Textarea::make('remarks')
                    ->label('Remarks')
                    ->nullable()
                    ->columnSpanFull()
                    ->rows(3),
            ]),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm());
    }
}
