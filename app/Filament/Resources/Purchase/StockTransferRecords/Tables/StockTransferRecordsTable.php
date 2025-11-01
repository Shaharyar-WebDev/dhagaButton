<?php

namespace App\Filament\Resources\Purchase\StockTransferRecords\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Support\Actions\CustomAction;

class StockTransferRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('str_number')
                    ->label('STR Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rawMaterial.name')
                    ->label('Raw Material')
                    ->sortable(),

                TextColumn::make('fromSupplier.name')
                    ->label('From Supplier')
                    ->placeholder('Factory')
                    ->sortable(),

                TextColumn::make('toSupplier.name')
                    ->label('To Supplier')
                    ->sortable(),

                TextColumn::make('challan_no')
                    ->label('Challan No.')
                    ->sortable(),

                TextColumn::make('challan_date')
                    ->label('Challan Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'verified',
                    ])
                    ->sortable(),

                IconColumn::make('locked')
                    ->boolean()
                    ->label('Locked'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('str_number')
                    ->schema([
                        TextInput::make('str_number')
                            ->label('STR No.')
                            ->placeholder('Enter STR number...'),
                    ])
                    ->query(
                        fn($query, $data) =>
                        $query->when(
                            $data['str_number'],
                            fn($q, $value) =>
                            $q->where('str_number', 'like', "%{$value}%")
                        )
                    )
                    ->indicateUsing(
                        fn($data) =>
                        $data['str_number'] ? 'STR Number: ' . $data['str_number'] : null
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    // CustomAction::unlock(),
                    CustomAction::viewAttachments('images/stock-transfer-records'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
