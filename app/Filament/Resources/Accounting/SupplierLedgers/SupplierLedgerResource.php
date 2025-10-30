<?php

namespace App\Filament\Resources\Accounting\SupplierLedgers;

use App\Filament\Support\Traits\NavigationGroup;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Accounting\SupplierLedger;
use App\Filament\Support\Traits\ReadOnlyResource;
use App\Filament\Resources\Accounting\SupplierLedgers\Pages\EditSupplierLedger;
use App\Filament\Resources\Accounting\SupplierLedgers\Pages\ViewSupplierLedger;
use App\Filament\Resources\Accounting\SupplierLedgers\Pages\ListSupplierLedgers;
use App\Filament\Resources\Accounting\SupplierLedgers\Pages\CreateSupplierLedger;
use App\Filament\Resources\Accounting\SupplierLedgers\Schemas\SupplierLedgerForm;
use App\Filament\Resources\Accounting\SupplierLedgers\Tables\SupplierLedgersTable;
use App\Filament\Resources\Accounting\SupplierLedgers\Schemas\SupplierLedgerInfolist;

class SupplierLedgerResource extends Resource
{
    use NavigationGroup;
    use ReadOnlyResource;
    protected static ?string $model = SupplierLedger::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'supplier_id';

    public static function form(Schema $schema): Schema
    {
        return SupplierLedgerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplierLedgerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierLedgersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplierLedgers::route('/'),
            // 'create' => CreateSupplierLedger::route('/create'),
            // 'view' => ViewSupplierLedger::route('/{record}'),
            // 'edit' => EditSupplierLedger::route('/{record}/edit'),
        ];
    }
}
