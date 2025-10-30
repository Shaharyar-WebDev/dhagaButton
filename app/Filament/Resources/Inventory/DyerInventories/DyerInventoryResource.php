<?php

namespace App\Filament\Resources\Inventory\DyerInventories;

use App\Filament\Resources\Inventory\DyerInventories\Pages\CreateDyerInventory;
use App\Filament\Resources\Inventory\DyerInventories\Pages\EditDyerInventory;
use App\Filament\Resources\Inventory\DyerInventories\Pages\ListDyerInventories;
use App\Filament\Resources\Inventory\DyerInventories\Pages\ViewDyerInventory;
use App\Filament\Resources\Inventory\DyerInventories\Schemas\DyerInventoryForm;
use App\Filament\Resources\Inventory\DyerInventories\Schemas\DyerInventoryInfolist;
use App\Filament\Resources\Inventory\DyerInventories\Tables\DyerInventoriesTable;
use App\Filament\Support\Traits\ReadOnlyResource;
use App\Models\Inventory\DyerInventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DyerInventoryResource extends Resource
{
    use ReadOnlyResource;
    protected static ?string $model = DyerInventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'dyer_id';

    public static function form(Schema $schema): Schema
    {
        return DyerInventoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DyerInventoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DyerInventoriesTable::configure($table);
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
            'index' => ListDyerInventories::route('/'),
            'create' => CreateDyerInventory::route('/create'),
            // 'view' => ViewDyerInventory::route('/{record}'),
            // 'edit' => EditDyerInventory::route('/{record}/edit'),
        ];
    }
}
