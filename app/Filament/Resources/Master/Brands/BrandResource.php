<?php

namespace App\Filament\Resources\Master\Brands;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\Master\Brand;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Master\Brands\Pages\EditBrand;
use App\Filament\Resources\Master\Brands\Pages\ListBrands;
use App\Filament\Resources\Master\Brands\Pages\CreateBrand;
use App\Filament\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Resources\Master\Brands\Tables\BrandsTable;

class BrandResource extends Resource
{
    use NavigationGroup;
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
