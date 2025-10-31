<?php

namespace App\Filament\Resources\Inventory\DyerInventories\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Services\UnitService;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;

class DyerInventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('fromSupplier.name')
                    ->label('From Supplier')
                    ->placeholder('Factory')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('dyer.name')
                    ->label('Dyer')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('str.str_number')
                    ->label('Stock Transfer Record')
                    ->placeholder('---')
                    ->toggleable()
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
                    ->searchable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->placeholder('-')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('issue')
                    ->numeric(2)
                    ->toggleable()
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->sortable()
                    ->label('Issue'),

                TextColumn::make('receive')
                    ->numeric(2)
                    ->toggleable()
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->sortable()
                    ->label('Receive'),

                TextColumn::make('balance')
                    ->numeric(2)
                    ->toggleable()
                    ->suffix(fn($record) => ' ' . $record->rawMaterial?->unit?->symbol)
                    ->sortable()
                    ->label('Balance'),

                TextColumn::make('issue_all')
                    ->label('Issue (Other Units)')
                    ->toggleable()
                    ->getStateUsing(function ($record) {
                        $output = [];
                        foreach (UnitService::getUnits() as $unit) {
                            $converted = $record->rawMaterial?->unit?->convertTo($unit, $record->issue ?? 0);
                            $output[] = "{$unit->symbol}: " . number_format($converted, 2);
                        }
                        return implode("<br>", $output);
                    })
                    ->html()
                    ->alignRight()
                    ->color('info'),

                TextColumn::make('receive_all')
                    ->label('Receive (Other Units)')
                    ->toggleable()
                    ->getStateUsing(function ($record) {
                        $output = [];
                        foreach (UnitService::getUnits() as $unit) {
                            if ($record->rawMaterial?->unit->id !== $unit->id) {
                                $converted = $record->rawMaterial?->unit?->convertTo($unit, $record->receive ?? 0);
                                $output[] = "{$unit->symbol}: " . number_format($converted, 2);
                            }
                        }
                        return implode("<br>", $output);
                    })
                    ->html()
                    ->alignRight()
                    ->color('info'),

                TextColumn::make('balance_all')
                    ->label('Balance (Other Units)')
                    ->toggleable()
                    ->getStateUsing(function ($record) {
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

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->toggleable()
                    ->limit(50)
                    ->label('Remarks')
                    ->formatStateUsing(function ($state) {
                        return Str::limit($state, 30, '...');
                    })
                    ->tooltip(function ($state) {
                        return $state;
                    })
                // ->wrap()
                ,

                TextColumn::make('created_at')
                    ->label('Created')
                    ->toggleable()
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('from_supplier_id')
                    ->label('From Supplier')
                    ->relationship('fromSupplier', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('dyer_id')
                    ->label('Dyer')
                    ->relationship('dyer', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->label('Brand'),
            ])
            ->recordActions([
                // ViewAction::make(),
                DeleteAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
