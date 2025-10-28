<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories;

use App\Filament\Resources\Inventory\RawMaterialInventories\Pages\CreateRawMaterialInventory;
use App\Filament\Resources\Inventory\RawMaterialInventories\Pages\EditRawMaterialInventory;
use App\Filament\Resources\Inventory\RawMaterialInventories\Pages\ListRawMaterialInventories;
use App\Filament\Resources\Inventory\RawMaterialInventories\Schemas\RawMaterialInventoryForm;
use App\Filament\Resources\Inventory\RawMaterialInventories\Tables\RawMaterialInventoriesTable;
use App\Models\Inventory\RawMaterialInventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RawMaterialInventoryResource extends Resource
{
    protected static ?string $model = RawMaterialInventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            'create' => CreateRawMaterialInventory::route('/create'),
            'edit' => EditRawMaterialInventory::route('/{record}/edit'),
        ];
    }
}
