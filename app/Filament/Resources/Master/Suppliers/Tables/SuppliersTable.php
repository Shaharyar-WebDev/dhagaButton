<?php

namespace App\Filament\Resources\Master\Suppliers\Tables;

use Filament\Tables\Table;
use App\Models\Master\Supplier;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                TextColumn::make('contact')
                    ->label('Contact')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('agreed_upon_rate_per_unit')
                    ->label('Rate/Unit')
                    ->money('PKR', true)
                    ->sortable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(30)
                    ->toggleable()
                    // ->searchable()
                    ->tooltip(fn($record) => $record->address),

                TextColumn::make('created_at')
                    ->toggleable()
                    // ->label('Added On')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('id')
                    ->label('Supplier name')
                    ->options(Supplier::options('name'))
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
