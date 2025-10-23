<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders\Schemas;

use Filament\Schemas\Schema;
use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use App\Models\Purchase\PurchaseOrder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Order Details')
                    // ->description('Define supplier, item, and financial details.')
                    ->schema([
                        // TextInput::make('po_number')
                        //     ->label('PO Number')
                        //     ->placeholder('Auto-generated or enter manually')
                        //     ->required()
                        //     ->unique(ignoreRecord: true)
                        //     ->maxLength(50)
                        //     ->helperText('Ensure this number matches your physical PO or sequence.'),

                        Select::make('raw_material_id')
                            ->label('Raw Material')
                            // ->options(RawMaterial::options('name'))
                            ->relationship('rawMaterial', 'name')
                            ->searchable()
                            ->preload()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                // $unitName = RawMaterial::select('id', 'unit_id')
                                //     ->with(['unit:id,name'])
                                //     ->findOrFail($state)
                                //     ->unit
                                //     ->name;
                                $unitName = DB::table('raw_materials')
                                    ->join('units', 'raw_materials.unit_id', '=', 'units.id')
                                    ->where('raw_materials.id', $state)
                                    ->value('units.symbol');

                                $set('unit_name', $unitName);
                            })
                            ->afterStateHydrated(function ($state, $set) {
                                // $unitName = RawMaterial::select('id', 'unit_id')
                                //     ->with(['unit:id,name'])
                                //     ->findOrFail($state)
                                //     ->unit
                                //     ->name;
                                $unitName = DB::table('raw_materials')
                                    ->join('units', 'raw_materials.unit_id', '=', 'units.id')
                                    ->where('raw_materials.id', $state)
                                    ->value('units.symbol');

                                $set('unit_name', $unitName);
                            })
                            ->required()
                            ->helperText('Select which material is being purchased.'),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'name')
                            // ->options(Supplier::options('name'))
                            ->manageOptionForm(SupplierForm::getForm())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // $agreedUponRatePerUnit = Supplier::findOrFail($state)->agreed_upon_rate_per_unit;
                                $agreedUponRatePerUnit = DB::table('suppliers')
                                    ->where('id', $state)
                                    ->value('agreed_upon_rate_per_unit') ?? 0;

                                $set('rate', $agreedUponRatePerUnit);
                            })
                            ->helperText('Select the supplier for this purchase order.'),

                        Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            // ->options(Supplier::options('name'))
                            ->manageOptionForm(BrandForm::getForm())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->required(),

                        TextInput::make('ordered_quantity')
                            ->label('Ordered Quantity')
                            ->numeric()
                            ->step('0.01')
                            ->required()
                            ->reactive()
                            ->rules(function ($record) {
                                if ($record?->exists && $record->deliveryOrders()->exists()) {

                                    $poDoQty = $record->deliveryOrders()->sum('quantity');
                                    return [
                                        "min:$poDoQty",
                                    ];
                                }

                                return ['min:1'];
                            })
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $rate = (float) $get('rate') ?? 0;
                                $set('total_amount', $state * $rate);
                            })
                            // ->prefix(function ($record) {
                            //     if ($record) {
                            //         $rcvdQty = $record->verified_delivery_orders_sum_quantity;
                            //         if (!$rcvdQty) {
                            //             return;
                            //         }
                            //         return "$rcvdQty Received";
                            //     }
                            // })
                            ->suffix(function ($state, $set, $get) {
                                return $get('unit_name') ?? '';
                            })
                            ->helperText(function ($record) {
                                if ($record) {
                                    $rcvdQty = $record->verified_delivery_orders_sum_quantity;
                                    if (!$rcvdQty) {
                                        return;
                                    }
                                    return "$rcvdQty Already Received";
                                }
                            }),



                        TextInput::make('rate')
                            ->label('Rate per Unit')
                            ->numeric()
                            ->prefix('PKR')
                            ->step('1')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $qty = (float) $get('ordered_quantity') ?? 0;
                                $set('total_amount', $qty * $state);
                            })
                            ->helperText('Agreed purchase rate per unit.'),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('PKR')
                            ->disabled()
                            ->dehydrated() // still saves value
                            // ->helperText('Automatically calculated (quantity × rate).')
                            ->step('1'),

                        ToggleButtons::make('status')
                            ->label('Status')
                            ->options(PurchaseOrder::getStatusOptionAttr()['options'])
                            ->default('pending')
                            ->required()
                            ->inline() // shows buttons side-by-side
                            ->colors(PurchaseOrder::getStatusOptionAttr()['colors'])
                            ->icons(PurchaseOrder::getStatusOptionAttr()['icons'])
                            ->helperText('Track current order progress.'),

                        TextInput::make('expected_delivery_date')
                            ->label('Expected Delivery Date')
                            ->type('date')
                            ->required(),

                        Textarea::make('remarks')
                            ->label('Remarks')
                            // ->placeholder('Add any special instructions or remarks...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->maxLength(500),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
