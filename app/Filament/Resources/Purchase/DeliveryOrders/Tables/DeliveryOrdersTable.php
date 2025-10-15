<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class DeliveryOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('do_number')
                    ->label('DO Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('delivery_order_reference')
                    ->label('Reference')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('challan_reference')
                    ->label('Challan No.')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('purchaseOrder.po_number')
                    ->label('PO Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('twister.name')
                    ->label('Twister')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignRight()
                    ->sortable()
                    ->formatStateUsing(
                        fn($state, $record) =>
                        number_format($state, 2) . ' ' . ($record->rawMaterial?->unit?->symbol ?? '')
                    ),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
