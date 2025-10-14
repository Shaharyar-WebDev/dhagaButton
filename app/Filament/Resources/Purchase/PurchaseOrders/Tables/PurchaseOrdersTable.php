<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('po_number')
                    ->label('PO No.')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable()
                    ->toggleable(), // allows hiding/showing in table view

                TextColumn::make('rawMaterial.name')
                    ->label('Material')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('ordered_quantity')
                    ->label('Quantity')
                    ->suffix(fn($record) => ' ' . ($record->rawMaterial?->unit?->symbol ?? ''))
                    ->numeric(3)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('rate')
                    ->label('Rate per Unit')
                    ->money('PKR', true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('PKR', true)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => $record->status_color)
                    ->sortable()
                    ->toggleable()
                    ->searchable(), // if you want to filter by status

                TextColumn::make('expected_delivery_date')
                    ->label('Expected Delivery')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->searchable(), // optional if you want date search

                TextColumn::make('created_at')
                    ->label('Created On')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'partially_received' => 'Partially Received',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name'),
                SelectFilter::make('raw_material_id')
                    ->label('Material')
                    ->relationship('rawMaterial', 'name'),
                Filter::make('expected_delivery_date')
                    ->label('Delivery Date')
                    ->schema([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('to')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('expected_delivery_date', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->whereDate('expected_delivery_date', '<=', $data['to']));
                    }),
                // TernaryFilter::make('overdue')
                //     ->label('Overdue')
                //     ->trueLabel('Yes')
                //     ->falseLabel('No')
                //     ->queries(
                //         true: fn($q) => $q->where('status', '!=', 'completed')
                //             ->whereDate('expected_delivery_date', '<', now()),
                //         false: fn($q) => $q->where(function ($query) {
                //             $query->where('status', 'completed')
                //                 ->orWhereDate('expected_delivery_date', '>=', now());
                //         })
                //     ),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
