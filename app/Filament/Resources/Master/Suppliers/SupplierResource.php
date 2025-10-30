<?php

namespace App\Filament\Resources\Master\Suppliers;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Supplier;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Master\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Master\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Master\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Master\Suppliers\Tables\SuppliersTable;

class SupplierResource extends Resource
{
    use NavigationGroup;
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
