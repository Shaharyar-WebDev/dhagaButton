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
                                    'do_number' => $record->deliveryOrder->do_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('purchaseOrder.po_number')
                    ->label('Purchase Order')
                    ->url(function ($record) {
                        return PurchaseOrderResource::getUrl('index', [
                            'filters' => [
                                'po_number' => [
                                    'po_number' => $record->purchaseOrder->po_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('twister.name')
                    ->label('Twister')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('debit')
                    ->label('Debit')
                    ->numeric(2)
                    ->color('success')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('credit')
                    ->label('Credit')
                    ->numeric(2)
                    ->color('danger')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->suffix(fn($record) => $record->rawMaterial?->unit?->symbol)
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
            ->defaultSort('twister_inventories.created_at', 'desc')
            ->filters([
                SelectFilter::make('twister_id')
                    ->relationship('twister', 'name')
                    ->label('Twister'),
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
