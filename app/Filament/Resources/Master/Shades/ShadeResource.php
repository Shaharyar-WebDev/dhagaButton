<?php

namespace App\Filament\Resources\Master\Shades;

use App\Filament\Resources\Master\Shades\Pages\CreateShade;
use App\Filament\Resources\Master\Shades\Pages\EditShade;
use App\Filament\Resources\Master\Shades\Pages\ListShades;
use App\Filament\Resources\Master\Shades\Schemas\ShadeForm;
use App\Filament\Resources\Master\Shades\Tables\ShadesTable;
use App\Filament\Support\Traits\NavigationGroup;
use App\Models\Master\Shade;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShadeResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = Shade::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ShadeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShadesTable::configure($table);
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
            'index' => ListShades::route('/'),
            // 'create' => CreateShade::route('/create'),
            // 'edit' => EditShade::route('/{record}/edit'),
        ];
    }
}
