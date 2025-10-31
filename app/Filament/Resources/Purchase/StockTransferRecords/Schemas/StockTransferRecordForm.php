<?php

namespace App\Filament\Resources\Purchase\StockTransferRecords\Schemas;

use App\Models\Master\Brand;
use Filament\Schemas\Schema;
use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Purchase\PurchaseOrder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Services\TwisterInventoryService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Models\Inventory\TwisterInventory;
use Filament\Infolists\Components\TextEntry;
use App\Services\RawMaterialInventoryService;
use App\Filament\Support\Actions\CustomAction;
use App\Models\Inventory\RawMaterialInventory;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class StockTransferRecordForm
{

    public static function getForm()
    {
        return [
            Section::make()->columnSpanFull()->columns(2)->schema([
                Grid::make(3)->columnSpanFull()->schema([

                    Select::make('raw_material_id')
                        ->label('Raw Material')
                        ->relationship('rawMaterial', 'name')
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if (request()->has('raw_material_id')) {
                                $set('raw_material_id', request('raw_material_id'));
                            }
                        })
                        ->default(request('raw_material_id'))
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

                    Select::make('from_supplier_id')
                        ->label('From Supplier')
                        ->relationship('fromSupplier', 'name')
                        ->preload()
                        ->manageOptionForm(SupplierForm::getForm())
                        ->reactive()
                        ->searchable()
                        // ->required()
                        ->dehydrated(),

                    Select::make('to_supplier_id')
                        ->label('To Supplier')
                        ->relationship('toSupplier', 'name')
                        ->preload()
                        ->manageOptionForm(SupplierForm::getForm())
                        ->reactive()
                        ->searchable()
                        ->required()
                        ->dehydrated(),
                ]),

                TextInput::make('challan_no')
                    ->label('Challan Number')
                    ->nullable()
                    ->maxLength(255),

                DatePicker::make('challan_date')
                    ->label('Challan Date')
                    ->required(),

                // 💡 INVENTORY DISPLAY SECTION
                Section::make('Raw Material Inventory')
                    ->reactive()
                    ->visible(fn($get) => filled($get('raw_material_id')))
                    ->columnSpanFull()
                    ->hidden(fn($get) => filled($get('from_supplier_id')))
                    ->schema([
                        TextEntry::make('inventory_info')
                            ->label('')
                            ->html()
                            ->state(function ($get) {
                                $rawMaterialId = $get('raw_material_id');
                                if (!$rawMaterialId)
                                    return '<em>Select a raw material to view inventory.</em>';

                                // Fetch per-brand inventory for this material
                                $inventories = RawMaterialInventory::query()
                                    ->where('raw_material_id', $rawMaterialId)
                                    ->select('brand_id', DB::raw('SUM(in_qty - out_qty) as balance'))
                                    ->groupBy('brand_id')
                                    ->with('brand:id,name')
                                    ->get();

                                if ($inventories->isEmpty()) {
                                    return '<em>No inventory records found for this material.</em>';
                                }

                                $balances = $inventories
                                    ->filter(fn($item) => $item->balance != 0)
                                    ->mapToGroups(fn($item) => [
                                        'Inventory' => [
                                            $item->brand->name ?? 'Unknown' => $item->balance,
                                        ],
                                    ])
                                    ->mapWithKeys(fn($item, $key) => [$key => $item->collapse()])
                                    ->toArray();

                                return RawMaterialInventoryService::renderHtml($balances, $get('raw_material_id'));
                            }),
                    ]),

                Section::make()
                    ->columnSpanFull()
                    ->reactive()
                    ->schema([
                        TextEntry::make('twisted_inventory_info')
                            ->label('Twisted Yarn Inventory')
                            ->html()
                            ->state(function ($get) {
                                $balances = TwisterInventoryService::getBalancesByTwisterAndBrand();
                                return TwisterInventoryService::renderHtml($balances, $get('raw_material_id'));
                            })
                    ])
                    ->visible(function ($get) {
                        if ($get('raw_material_id') && $get('from_supplier_id') && RawMaterial::find($get('raw_material_id'))?->type?->name == 'twisted_yarn') {
                            return true;
                        };

                        return false;
                    }),

                Repeater::make('items')
                    ->relationship()
                    ->defaultItems(0)
                    ->minItems(1)
                    ->addActionLabel('Add Item')
                    ->schema([

                        Select::make('brand_id')
                            ->label('Brand')
                            ->options(fn() => Brand::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->suffix(fn($get) => $get('../../unit_name'))
                            ->minValue(0.01)
                            ->step(0.01)
                            ->required()
                            ->prefixAction(CustomAction::unitConverter(
                                targetField: 'quantity',
                                getTargetUnit: fn($get) => RawMaterial::find($get('../../raw_material_id'))?->unit
                            ))
                            ->rules(function ($get) {
                                return [
                                    function ($attribute, $value, $fail) use ($get) {
                                        $brandId = $get('brand_id');
                                        $supplierId = $get('../../from_supplier_id');
                                        $rawMaterialId = $get('../../raw_material_id');

                                        if (!$brandId || !$rawMaterialId)
                                            return;

                                        // 🧮 Determine source inventory
                                        if ($supplierId) {
                                            // CASE 1: Supplier selected → Twister inventory
                                            $balance = TwisterInventory::where('twister_id', $supplierId)
                                                ->where('brand_id', $brandId)
                                                ->sum(DB::raw('issue - receive'));
                                        } else {
                                            // CASE 2: No supplier → Raw material inventory
                                            $balance = RawMaterialInventory::where('raw_material_id', $rawMaterialId)
                                                ->where('brand_id', $brandId)
                                                ->sum(DB::raw('in_qty - out_qty'));
                                        }

                                        // 🧾 Sum total for this brand from current repeater state
                                        $repeaterItems = $get('../../items') ?? [];
                                        $totalEntered = collect($repeaterItems)
                                            ->where('brand_id', $brandId)
                                            ->sum('quantity');

                                        if ($totalEntered > $balance) {
                                            $fail("Total quantity for this brand exceeds available balance ({$balance} Kg).");
                                        }
                                    },
                                ];
                            })
                            ->helperText(function ($get) {
                                $brandId = $get('brand_id');
                                $supplierId = $get('../../from_supplier_id');
                                $rawMaterialId = $get('../../raw_material_id');

                                if (!$brandId || !$rawMaterialId)
                                    return '';

                                if ($supplierId) {
                                    // 🧵 From Twister Inventory
                                    $balance = TwisterInventory::where('twister_id', $supplierId)
                                        ->where('brand_id', $brandId)
                                        ->sum(DB::raw('issue - receive'));

                                    return "Available in Twister: {$balance} Kg";
                                } else {
                                    // 🏭 From Raw Material Inventory
                                    $balance = RawMaterialInventory::where('raw_material_id', $rawMaterialId)
                                        ->where('brand_id', $brandId)
                                        ->sum(DB::raw('in_qty - out_qty'));

                                    return "Available in Inventory: {$balance} Kg";
                                }
                            }),

                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->rows(2)
                            ->maxLength(255),

                    ])
                    ->columnSpanFull()
                    ->columns(3),

                FileUpload::make('attachments')
                    ->label('Attachments')
                    ->multiple()
                    // ->image()
                    ->directory('images/stock-transfer-records')
                    ->disk('public')
                    ->visibility('public')
                    ->deleteUploadedFileUsing(function ($file) {
                        Storage::disk('public')->delete($file);
                    })
                    ->nullable()
                    ->downloadable()
                    ->columnSpanFull()
                    // ->helperText('Optional: Upload scanned challan or image proof.')
                    // ->maxSize(2048)
                    ->openable(),


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
