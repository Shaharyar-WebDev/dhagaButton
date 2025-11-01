<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Services\UnitService;
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


                TextColumn::make('in_all')
                    ->label('In (Other Units)')
                    ->toggleable()
                    ->placeholder('---')
                    ->getStateUsing(function ($record) {
                        if (!in_array($record->rawMaterial?->type->name, ['twisted_yarn', 'yarns', 'dyed_yarn'])) {
                            return;
                        }
                        $output = [];
                        foreach (UnitService::getUnits() as $unit) {
                            if ($record->rawMaterial?->unit->id !== $unit->id) {
                                $converted = $record->rawMaterial?->unit?->convertTo($unit, $record->in_qty ?? 0);
                                $output[] = "{$unit->symbol}: " . number_format($converted, 2);
                            }
                        }
                        return implode("<br>", $output);
                    })
                    ->html()
                    ->alignRight()
                    ->color('info'),

                TextColumn::make('out_all')
                    ->label('Out (Other Units)')
                    ->toggleable()
                    ->getStateUsing(function ($record) {
                        if (!in_array($record->rawMaterial?->type->name, ['twisted_yarn', 'yarns', 'dyed_yarn'])) {
                            return;
                        }
                        $output = [];
                        foreach (UnitService::getUnits() as $unit) {
                            if ($record->rawMaterial?->unit->id !== $unit->id) {
                                $converted = $record->rawMaterial?->unit?->convertTo($unit, $record->out_qty ?? 0);
                                $output[] = "{$unit->symbol}: " . number_format($converted, 2);
                            }
                        }
                        return implode("<br>", $output);
                    })
                    ->placeholder('---')
                    ->html()
                    ->alignRight()
                    ->color('info'),

                TextColumn::make('balance_all')
                    ->label('Balance (Other Units)')
                    ->toggleable()
                    ->placeholder('---')
                    ->getStateUsing(function ($record) {
                        if (!in_array($record->rawMaterial?->type->name, ['twisted_yarn', 'yarns', 'dyed_yarn'])) {
                            return;
                        }
                        $output = [];
                        foreach (UnitService::getUnits() as $unit) {
                            if ($record->rawMaterial?->unit->id !== $unit->id) {
                                $converted = $record->rawMaterial?->unit?->convertTo($unit, $record->balance ?? 0);
                                $output[] = "{$unit->symbol}: " . number_format($converted, 2);
                            }
                        }
                        return implode("<br>", $output);
                    })
                    ->html()
                    ->alignRight()
                    ->color('info'),

                // TextColumn::make('rate')
                //     ->numeric()
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->sortable(),

                // TextColumn::make('value')
                //     ->numeric()
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->sortable(),

                TextColumn::make('reference_type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Source Type')
                    ->formatStateUsing(fn($record) => class_basename($record->reference_type))
                    ->searchable(),

                TextColumn::make('reference_id')
                    ->label('Source Reference')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($record) => app($record->reference_type)::find($record->reference_id)->getTitleAttributeName())
                    ->sortable(),

                TextColumn::make('remarks')
                    ->formatStateUsing(function ($record) {
                        return Str::limit(app($record->reference_type)::find($record->reference_id)?->remarks ?? $record->remarks, 30, '...');
                    })
                    ->tooltip(function ($state) {
                        return $state;
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
