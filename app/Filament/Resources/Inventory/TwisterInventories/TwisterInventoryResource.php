<?php

namespace App\Filament\Resources\Inventory\TwisterInventories;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Inventory\TwisterInventory;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Support\Traits\ReadOnlyResource;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\EditTwisterInventory;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\ViewTwisterInventory;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\CreateTwisterInventory;
use App\Filament\Resources\Inventory\TwisterInventories\Pages\ListTwisterInventories;
use App\Filament\Resources\Inventory\TwisterInventories\Schemas\TwisterInventoryForm;
use App\Filament\Resources\Inventory\TwisterInventories\Tables\TwisterInventoriesTable;
use App\Filament\Resources\Inventory\TwisterInventories\Schemas\TwisterInventoryInfolist;

class TwisterInventoryResource extends Resource
{
    use NavigationGroup;
    use ReadOnlyResource;
    protected static ?string $model = TwisterInventory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-arrow-path';

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
        return TwisterInventoriesTable::configure($table)
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc'));
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
