<?php

namespace App\Filament\Resources\Master\Units;

use App\Filament\Resources\Master\Units\Pages\CreateUnit;
use App\Filament\Resources\Master\Units\Pages\EditUnit;
use App\Filament\Resources\Master\Units\Pages\ListUnits;
use App\Filament\Resources\Master\Units\Schemas\UnitForm;
use App\Filament\Resources\Master\Units\Tables\UnitsTable;
use App\Models\Master\Unit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitResource extends Resource
{
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
