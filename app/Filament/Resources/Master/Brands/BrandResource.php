<?php

namespace App\Filament\Resources\Master\Brands;

use App\Filament\Resources\Master\Brands\Pages\CreateBrand;
use App\Filament\Resources\Master\Brands\Pages\EditBrand;
use App\Filament\Resources\Master\Brands\Pages\ListBrands;
use App\Filament\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Resources\Master\Brands\Tables\BrandsTable;
use App\Models\Master\Brand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
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
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }
}
