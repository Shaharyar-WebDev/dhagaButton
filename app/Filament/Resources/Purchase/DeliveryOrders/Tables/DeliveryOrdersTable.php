<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Form;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
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
                    // ->copyable()
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
                    ->date('d M Y H:iA')
                    ->sortable(),

                TextColumn::make('created_at')
                    // ->label('Created')
                    ->dateTime('d M Y H:iA')
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
                SelectFilter::make('supplier')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('twister')
                    ->relationship('twister', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    CustomAction::verifyStatus(),
                    Action::make('view_attachments')
                        ->icon('heroicon-o-photo')
                        ->color('info')
                        ->schema([
                            FileUpload::make('attachments')
                                ->label('Attachments')
                                ->directory('images/delivery-orders')
                                ->disk('public')
                                ->visibility('public')
                                // ->multiple()
                                ->openable()
                                ->downloadable()
                                // ->placeholder(null)
                                ->previewable()
                                ->disabled()
                                ->deletable(false)
                                ->dehydrated(false),
                        ])
                        ->mountUsing(function ($form, $record) {
                            $attachments = $record->attachments ?? [];

                            $form->fill(['attachments' => $attachments]);
                        })
                        ->modalSubmitAction(false)
                        ->modalWidth('3xl'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
