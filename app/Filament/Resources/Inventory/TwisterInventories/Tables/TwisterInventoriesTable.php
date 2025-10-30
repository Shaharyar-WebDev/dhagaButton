<?php

namespace App\Filament\Resources\Inventory\TwisterInventories\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use App\Filament\Resources\Purchase\PurchaseOrders\PurchaseOrderResource;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;

class TwisterInventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('deliveryOrder.do_number')
                    ->label('Delivery Order')
                    ->url(function ($record) {
                        return DeliveryOrderResource::getUrl('index', [
                            'filters' => [
                                'do_number' => [
                                    'do_number' => $record->deliveryOrder?->do_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->placeholder('---')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('purchaseOrder.po_number')
                    ->label('Purchase Order')
                    ->placeholder('---')
                    ->url(function ($record) {
                        return PurchaseOrderResource::getUrl('index', [
                            'filters' => [
                                'po_number' => [
                                    'po_number' => $record->purchaseOrder?->po_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('grn.grn_number')
                    ->label('Goods Received Note')
                    ->placeholder('---')
                    ->url(function ($record) {
                        return GoodsReceivedNoteResource::getUrl('index', [
                            'filters' => [
                                'grn_number' => [
                                    'grn_number' => $record->grn?->grn_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('str.str_number')
                    ->label('Stock Transfer Record')
                    ->placeholder('---')
                    ->url(function ($record) {
                        return StockTransferRecordResource::getUrl('index', [
                            'filters' => [
                                'str_number' => [
                                    'str_number' => $record->str?->str_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('challan_reference')
                    ->label('Challan Reference')
                    ->toggleable()
                    ->copyable()
                    ->placeholder('---')
                    ->getStateUsing(function ($record) {
                        return $record->deliveryOrder->challan_reference
                            ?? $record->grn?->challan_no
                            ?? $record->str?->challan_no
                            ?? '---';
                    })
                    ->searchable(query: function ($query, $search) {
                        $query
                            ->whereHas('deliveryOrder', function ($q) use ($search) {
                                $q->where('challan_reference', 'like', "%{$search}%");
                            })
                            ->orWhereHas('grn', function ($q) use ($search) {
                                $q->where('challan_no', 'like', "%{$search}%");
                            });
                    }),

                TextColumn::make('delivery_order_reference')
                    ->label('Delivery Order Ref')
                    ->toggleable()
                    ->copyable()
                    ->placeholder('---')
                    ->getStateUsing(fn($record) => $record?->deliveryOrder?->delivery_order_reference)
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('deliveryOrder', function ($q) use ($search) {
                            $q->where('delivery_order_reference', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable()
                    ->placeholder('---')
                    ->toggleable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('twister.name')
                    ->label('Twister')
                    ->searchable()
                    ->placeholder('---')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('dyer.name')
                    ->label('Dyer')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Factory')
                    ->toggleable(),

                TextColumn::make('issue')
                    ->label('Issue')
                    ->numeric(2)
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->color('danger')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('receive')
                    ->label('Receive')
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->numeric(2)
                    ->color('success')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->numeric(2)
                    ->color('primary')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->formatStateUsing(function ($state) {
                        return Str::limit($state, 30, '...');
                    })
                    ->tooltip(function ($state) {
                        return $state;
                    })
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d-M-Y H:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('twister')
                    ->relationship('twister', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Brand'),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
