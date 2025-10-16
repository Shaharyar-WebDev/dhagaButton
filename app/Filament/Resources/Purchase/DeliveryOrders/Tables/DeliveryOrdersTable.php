<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\PurchaseOrders\PurchaseOrderResource;

class DeliveryOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('do_number')
                    ->label('DO No.')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->copyMessageDuration(1000)
                    ->toggleable(),

                TextColumn::make('purchaseOrder.po_number')
                    ->label('PO No.')
                    ->url(function ($record) {
                        return PurchaseOrderResource::getUrl('index', [
                            'filters' => [
                                'po_number' => [
                                    'po_number' => $record->purchaseOrder->po_number
                                ]
                            ],
                        ]);
                    }, true)
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('twister.name')
                    ->label('Twister')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('challan_reference')
                    ->label('Challan Reference')
                    ->sortable()
                    ->copyable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('delivery_order_reference')
                    ->label('D/O Reference')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->suffix(fn($record) => ' ' . ($record->rawMaterial->unit->symbol ?? ''))
                    ->alignRight()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'verified',
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('challan_date')
                    ->label('Challan Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    // ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('challan_date', 'desc')
            ->filters([
                Filter::make('do_number')
                    ->schema([
                        TextInput::make('do_number')
                            ->label('Delivery Order No.')
                            ->placeholder('Enter DO number...'),
                    ])
                    ->query(
                        fn($query, $data) =>
                        $query->when(
                            $data['do_number'],
                            fn($q, $value) =>
                            $q->where('do_number', 'like', "%{$value}%")
                        )
                    )
                    ->indicateUsing(
                        fn($data) =>
                        $data['do_number'] ? 'DO Number: ' . $data['do_number'] : null
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    CustomAction::verifyDo(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
