<?php

namespace App\Filament\Resources\Master\Units\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class UnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('symbol')
                    ->label('Symbol')
                    ->placeholder('-'),
                TextColumn::make('baseUnit.name')
                    ->placeholder('-')
                    ->label('Base Unit'),
                TextColumn::make('conversion_operator')
                    ->placeholder('-')
                    ->label('Operator'),
                TextColumn::make('conversion_value')
                    ->placeholder('-')
                    ->label('Value'),
                // TextColumn::make('created_at')
                    // ->dateTime()
                    // ->label('Created'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
