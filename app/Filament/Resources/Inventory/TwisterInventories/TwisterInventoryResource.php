<?php

namespace App\Filament\Resources\Inventory\TwisterInventories;

use App\Filament\Resources\Inventory\TwisterInventories\Pages\CreateTwisterInventory;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\EditTwisterInventory;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\ListTwisterInventories;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\ViewTwisterInventory;
use App\Filament\Resources\Inventory\TwisterInventories\Schemas\TwisterInventoryForm;
use App\Filament\Resources\Inventory\TwisterInventories\Schemas\TwisterInventoryInfolist;
use App\Filament\Resources\Inventory\TwisterInventories\Tables\TwisterInventoriesTable;
use App\Filament\Support\Traits\ReadOnlyResource;
use App\Models\Inventory\TwisterInventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TwisterInventoryResource extends Resource
{
    use ReadOnlyResource;
    protected static ?string $model = TwisterInventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'twister_id';

    public static function form(Schema $schema): Schema
    {
        return TwisterInventoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TwisterInventoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TwisterInventoriesTable::configure($table);
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
            'index' => ListTwisterInventories::route('/'),
            // 'create' => CreateTwisterInventory::route('/create'),
            // 'view' => ViewTwisterInventory::route('/{record}'),
            // 'edit' => EditTwisterInventory::route('/{record}/edit'),
        ];
    }
}
