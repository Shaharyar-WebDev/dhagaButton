<?php

namespace App\Filament\Resources\Master\Units;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\Master\Unit;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Master\Units\Pages\EditUnit;
use App\Filament\Resources\Master\Units\Pages\ListUnits;
use App\Filament\Resources\Master\Units\Pages\CreateUnit;
use App\Filament\Resources\Master\Units\Schemas\UnitForm;
use App\Filament\Resources\Master\Units\Tables\UnitsTable;

class UnitResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = Unit::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $recordTitleAttribute = 'symbol';

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitsTable::configure($table);
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
            'index' => ListUnits::route('/'),
            // 'create' => CreateUnit::route('/create'),
            // 'edit' => EditUnit::route('/{record}/edit'),
        ];
    }
}
