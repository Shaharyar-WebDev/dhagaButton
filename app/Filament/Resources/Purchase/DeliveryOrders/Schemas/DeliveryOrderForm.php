<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Schemas;

use Filament\Schemas\Schema;
use App\Models\Master\Supplier;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use App\Models\Purchase\PurchaseOrder;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class DeliveryOrderForm
{
    public static function updateFields($state, $set)
    {
        if (!$state)
            return;

        $po = PurchaseOrder::with('rawMaterial.unit')->find($state);

        if ($po) {
            $set('raw_material_id', $po->raw_material_id);
            $set('brand_id', $po->brand_id);
            $set('supplier_id', $po->supplier_id);
            $set('unit_name', $po->rawMaterial?->unit?->symbol);
            $set('max_quantity', $po->ordered_quantity);

            $doQty = $po->deliveryOrders?->sum('quantity') ?? 0;
            $poQty = $po->ordered_quantity ?? 0;

            $set('available_quantity', $poQty - $doQty);
        }
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delivery Order Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('purchase_order_id')
                                    ->label('Purchase Order')
                                    ->relationship('pendingPurchaseOrder', 'po_number')
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateHydrated(fn($state, $set) => self::updateFields($state, $set))
                                    ->afterStateUpdated(fn($state, $set) => self::updateFields($state, $set))
                                    ->preload()
                                    // ->helperText('Supplier or broker who provided the yarn.')
                                    ->required(),

                                Select::make('raw_material_id')
                                    ->label('Raw Material')
                                    ->relationship('rawMaterial', 'name')
                                    ->disabled()
                                    ->dehydrated()
                                    ->searchable()
                                    ->preload()
                                    // ->helperText('Supplier or broker who provided the yarn.')
                                    ->required(),

                                Select::make('brand_id')
                                    ->label('Brand')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->disabled()
                                    ->dehydrated()
                                    ->preload()
                                    ->required(),

                                Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->relationship('supplier', 'name')
                                    ->searchable()
                                    ->disabled()
                                    ->dehydrated()
                                    ->preload()
                                    // ->helperText('Supplier or broker who provided the yarn.')
                                    ->required(),

                                Select::make('twister_id')
                                    ->label('Twister')
                                    ->relationship('twister', 'name')
                                    ->searchable()
                                    ->manageOptionForm(SupplierForm::getForm())
                                    ->preload()
                                    // ->helperText('Twister to whom the yarn is being delivered.')
                                    ->required(),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->required()
                                    ->step(0.01)
                                    ->rules(function ($state, $set, $get) {
                                        $maxQty = $get('max_quantity');
                                        return [
                                            "max:$maxQty"
                                        ];
                                    })
                                    // ->helperText('Quantity of yarn being delivered.')
                                    // ->suffix(fn($get) => ' ' . $get('unit_name'))
                                    ->suffix(function ($state, $set, $get) {
                                        $qty = $get('available_quantity');
                                        $unitName = $get('unit_name');
                                        if ($qty) {
                                            return "$qty $unitName Reamining";
                                        }
                                    }),

                                Textarea::make('remarks')
                                    ->label('Remarks')
                                    ->rows(3)
                                    ->placeholder('Remarks...')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('challan_reference')
                                    ->label('Challan No.')
                                    ->required()
                                    ->placeholder('e.g. CH-882'),

                                TextInput::make('delivery_order_reference')
                                    ->label('D/O No.')
                                    ->maxLength(100)
                                    ->placeholder('Internal or broker reference'),


                                FileUpload::make('attachments')
                                    ->label('Attachments')
                                    ->directory('delivery-orders')
                                    ->multiple()
                                    // ->image()
                                    ->directory('images/delivery-orders/')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->deleteUploadedFileUsing(function ($file) {
                                        Storage::disk('public')->delete($file);
                                    })
                                    ->nullable()
                                    ->downloadable()
                                    // ->helperText('Optional: Upload scanned challan or image proof.')
                                    // ->maxSize(2048)
                                    ->openable(),
                            ])
                            ->columnSpanFull(),

                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
