<?php

namespace App\Filament\Resources\Master\Suppliers;

use App\Filament\Resources\Master\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Master\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Master\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Master\Suppliers\Tables\SuppliersTable;
use App\Models\Master\Supplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SupplierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuppliersTable::configure($table);
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
            'index' => ListSuppliers::route('/'),
            // 'create' => CreateSupplier::route('/create'),
            // 'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
