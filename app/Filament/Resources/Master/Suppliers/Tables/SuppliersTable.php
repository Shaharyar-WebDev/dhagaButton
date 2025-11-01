<?php

namespace App\Filament\Resources\Master\Suppliers\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Illuminate\Support\Carbon;
use App\Models\Master\Supplier;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierLedgerExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Support\Actions\CustomAction;

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
                    ->placeholder('---')
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('---')
                    ->toggleable(),

                TextColumn::make('agreed_upon_rate_per_unit')
                    ->label('Rate/Unit')
                    ->placeholder('---')
                    ->money('PKR', true)
                    ->sortable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(30)
                    ->toggleable()
                    ->placeholder('---')
                    // ->searchable()
                    ->tooltip(fn($record) => $record->address),

                TextColumn::make('total_debit')
                    // ->label('Total Due')
                    ->money('PKR', true)
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->ledgers->sum('debit')),

                TextColumn::make('total_credit')
                    // ->label('Total Paid')
                    ->money('PKR', true)
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->ledgers->sum('credit')),

                TextColumn::make('balance')
                    // ->label('Balance')
                    ->money('PKR', true)
                    ->sortable()
                    ->color(fn($state) => $state < 0 ? 'danger' : ($state > 0 ? 'success' : 'secondary'))
                    ->getStateUsing(fn($record) => $record->ledgers->sum('credit') - $record->ledgers->sum('debit')),

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
                    DeleteAction::make(),
                    CustomAction::exportSupplierAccountingLedger(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
