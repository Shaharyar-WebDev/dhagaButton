<?php

namespace App\Filament\Resources\Master\RawMaterials;

use App\Filament\Resources\Master\RawMaterials\Pages\CreateRawMaterial;
use App\Filament\Resources\Master\RawMaterials\Pages\EditRawMaterial;
use App\Filament\Resources\Master\RawMaterials\Pages\ListRawMaterials;
use App\Filament\Resources\Master\RawMaterials\Schemas\RawMaterialForm;
use App\Filament\Resources\Master\RawMaterials\Tables\RawMaterialsTable;
use App\Models\Master\RawMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RawMaterialResource extends Resource
{
    protected static ?string $model = RawMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RawMaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RawMaterialsTable::configure($table);
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
            'index' => ListRawMaterials::route('/'),
            // 'create' => CreateRawMaterial::route('/create'),
            // 'edit' => EditRawMaterial::route('/{record}/edit'),
        ];
    }
}
