<?php

namespace App\Filament\Resources\Accounting\SupplierLedgers\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Form;
use App\Models\Purchase\DeliveryOrder;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Purchase\GoodsReceivedNote;
use App\Models\Purchase\StockTransferRecord;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;

class SupplierLedgersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('source_type')
                    ->label('Source Type')
                    ->formatStateUsing(function ($record) {
                        return class_basename($record->source_type);
                    })
                    ->searchable(),

                TextColumn::make('source_id')
                    ->label('Source Ref')
                    ->url(function ($record) {
                        // dd($record);
                        return match ($record->source_type) {
                            GoodsReceivedNote::class => GoodsReceivedNoteResource::getUrl('index', [
                                'filters' => [
                                    'grn_number' => [
                                        'grn_number' => GoodsReceivedNote::find($record->source_id)->first()->grn_number
                                    ]
                                ],
                            ]),
                            DeliveryOrder::class => DeliveryOrderResource::getUrl('index', [
                                'filters' => [
                                    'do_number' => [
                                        'do_number' => DeliveryOrder::find($record->source_id)->first()->do_number
                                    ]
                                ],
                            ]),
                            StockTransferRecord::class => StockTransferRecordResource::getUrl('index', [
                                'filters' => [
                                    'str_number' => [
                                        'str_number' => StockTransferRecord::find($record->source_id)->first()->str_number
                                    ]
                                ],
                            ])
                        };
                    })
                    ->formatStateUsing(function ($record) {
                        return $record->source->getTitleAttributeName();
                    }),

                // TextColumn::make('transaction_type')
                //     ->label('Transaction Type')
                //     ->sortable(),

                TextColumn::make('debit')
                    ->label('Debit')
                    ->numeric(3)
                    ->sortable(),

                TextColumn::make('credit')
                    ->label('Credit')
                    ->numeric(3)
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->numeric(3)
                    ->sortable()
                    ->color(fn($record) => $record->balance < 0 ? 'danger' : 'success'),

                TextColumn::make('reference_no')
                    ->label('Reference No.')
                    ->searchable(),

                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('remarks')
                    ->formatStateUsing(function ($state) {
                        return Str::limit($state, 30, '...');
                    })
                    ->tooltip(function ($state) {
                        return $state;
                    })
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),
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
