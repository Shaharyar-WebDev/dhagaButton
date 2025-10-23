<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Schemas;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\Master\Supplier;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use App\Models\Purchase\PurchaseOrder;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class DeliveryOrderForm
{
    public static function updateFields($state, $set)
    {
        if (!$state) {
            if (request()->has('purchase_order_id')) {
                $reqPoId = request()->query('purchase_order_id');
                $set('purchase_order_id', $reqPoId);
                $set('request_po_id', $reqPoId);
                $poId = $reqPoId;
            } else {
                return;
            }
        } else {
            $poId = $state;
        }

        $po = PurchaseOrder::with('rawMaterial.unit')->findOrFail($poId);

        if ($po) {
            $set('raw_material_id', $po->raw_material_id);
            $set('brand_id', $po->brand_id);
            $set('supplier_id', $po->supplier_id);
            $set('unit_name', $po->rawMaterial?->unit?->symbol);
            $set('max_quantity', $po->ordered_quantity);

            $doQty = $po->deliveryOrders()->sum('quantity') ?? 0;
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
                                    ->relationship('purchaseOrder', 'po_number')
                                    ->searchable()
                                    ->disabled(fn($get) => $get('id') || $get('request_po_id'))
                                    ->reactive()
                                    // ->default(fn() => request()->query('purchase_order_id'))
                                    ->afterStateHydrated(fn($state, $set) => self::updateFields($state, $set))
                                    ->afterStateUpdated(fn($state, $set) => self::updateFields($state, $set))
                                    ->preload()
                                    ->dehydrated()
                                    // ->helperText('Supplier or broker who provided the yarn.')
                                    ->required(),

                                Select::make('raw_material_id')
                                    ->label('Raw Material')
                                    ->relationship('rawMaterial', 'name')
                                    ->disabled()
                                    ->default(fn($get) => $get('raw_material_id'))
                                    ->dehydrated()
                                    ->searchable()
                                    ->preload()
                                    // ->helperText('Supplier or broker who provided the yarn.')
                                    ->required(),

                                Select::make('brand_id')
                                    ->label('Brand')
                                    ->default(fn($get) => $get('brand_id'))
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->manageOptionForm(SupplierForm::getForm())
                                    ->disabled()
                                    ->dehydrated()
                                    ->preload()
                                    ->required(),

                                Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->default(fn($get) => $get('supplier_id'))
                                    ->relationship('supplier', 'name')
                                    ->searchable()
                                    ->disabled()
                                    ->dehydrated()
                                    ->preload()
                                    // ->helperText('Supplier or broker who provided the yarn.')
                                    ->required(),

                                Select::make('twister_id')
                                    ->label('Twister')
                                    ->default(fn($get) => $get('twister_id'))
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
                                        $maxQty = $get('available_quantity');
                                        $recordId = $get('id'); // when editing, this will have a value

                                        if ($recordId) {
                                            // Editing existing record: skip strict max check
                                            return [];
                                        }
                                        return [
                                            "max:$maxQty"
                                        ];
                                    })
                                    // ->helperText('Quantity of yarn being delivered.')
                                    ->prefixAction(function ($state, $set, $get) {
                                        return Action::make('max_qty')
                                            ->icon('heroicon-o-plus')
                                            ->action(function () use ($set, $get) {
                                                $set('quantity', $get('available_quantity'));
                                            });
                                    })
                                    ->suffix(function ($state, $set, $get) {
                                        $qty = $get('available_quantity') ?? 0;
                                        $unitName = $get('unit_name');
                                        if ($get('purchase_order_id')) {
                                            if ($qty) {
                                                return "$qty $unitName Reamining";
                                            } else {
                                                return "No Qty Available";
                                            }
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

                                DatePicker::make('challan_date')
                                    ->label('Challan Date')
                                    ->required()
                                    ->default(now())
                                    // ->native(false)
                                    ->displayFormat('d M Y')
                                    ->closeOnDateSelection(),


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
                                    ->columnSpanFull()
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
