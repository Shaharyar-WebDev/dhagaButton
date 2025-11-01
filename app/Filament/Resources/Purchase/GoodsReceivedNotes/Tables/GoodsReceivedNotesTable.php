<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Support\Actions\CustomAction;

class GoodsReceivedNotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grn_number')
                    ->label('GRN Number')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('---')
                    ->searchable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('---')
                    ->searchable(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('---')
                    ->searchable(),

                TextColumn::make('purchaseOrder.po_number')
                    ->label('Purchase Order')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('---')
                    ->searchable(),

                TextColumn::make('challan_no')
                    ->label('Challan No')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('---')
                    ->searchable(),

                TextColumn::make('challan_date')
                    ->label('Challan Date')
                    ->date()
                    ->toggleable()
                    ->placeholder('---')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => $record->status_color)
                    ->sortable()
                    ->toggleable()
                    ->searchable(), // if you want to filter by status

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->toggleable()
                    ->placeholder('---')
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('grn_number')
                    ->schema([
                        TextInput::make('grn_number')
                            ->label('GRN No.')
                            ->placeholder('Enter GRN number...'),
                    ])
                    ->query(
                        fn($query, $data) =>
                        $query->when(
                            $data['grn_number'],
                            fn($q, $value) =>
                            $q->where('grn_number', 'like', "%{$value}%")
                        )
                    )
                    ->indicateUsing(
                        fn($data) =>
                        $data['grn_number'] ? 'GRN Number: ' . $data['grn_number'] : null
                    ),
                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name'),
                SelectFilter::make('purchase_order_id')
                    ->label('Purchase Order')
                    ->relationship('purchaseOrder', 'po_number'),
                SelectFilter::make('raw_material_id')
                    ->label('Material')
                    ->relationship('rawMaterial', 'name'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    CustomAction::verifyStatus(),
                    CustomAction::viewAttachments('images/good-received-notes')
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
