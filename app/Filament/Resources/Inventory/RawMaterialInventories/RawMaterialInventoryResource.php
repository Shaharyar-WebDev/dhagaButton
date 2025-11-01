<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Inventory\RawMaterialInventory;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Inventory\RawMaterialInventories\Pages\EditRawMaterialInventory;
use App\Filament\Resources\Inventory\RawMaterialInventories\Pages\CreateRawMaterialInventory;
use App\Filament\Resources\Inventory\RawMaterialInventories\Pages\ListRawMaterialInventories;
use App\Filament\Resources\Inventory\RawMaterialInventories\Schemas\RawMaterialInventoryForm;
use App\Filament\Resources\Inventory\RawMaterialInventories\Tables\RawMaterialInventoriesTable;

class RawMaterialInventoryResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = RawMaterialInventory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $recordTitleAttribute = 'raw_material_id';

    public static function form(Schema $schema): Schema
    {
        return RawMaterialInventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RawMaterialInventoriesTable::configure($table);
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
            'index' => ListRawMaterialInventories::route('/'),
            // 'create' => CreateRawMaterialInventory::route('/create'),
            // 'edit' => EditRawMaterialInventory::route('/{record}/edit'),
        ];
    }
}
