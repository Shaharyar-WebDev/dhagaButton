<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class RawMaterialInventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('in_qty')
                    ->label('In Qty')
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->numeric()
                    ->sortable(),

                TextColumn::make('out_qty')
                    ->label('Out Qty')
                    ->numeric()
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->sortable(),

                TextColumn::make('balance')
                    ->numeric()
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->sortable(),

                TextColumn::make('rate')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('value')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('reference_type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('reference_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('remarks')
                    ->formatStateUsing(function ($record) {
                        return app($record->reference_type)::find($record->reference_id)?->remarks ?? $record->remarks;
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('raw_material_id')
                    ->label('Raw Material')
                    ->relationship('rawMaterial', 'name'),
                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name'),
            ])
            ->recordActions([
                // ActionGroup::make([
                    // EditAction::make(),
                    DeleteAction::make(),
                // ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
