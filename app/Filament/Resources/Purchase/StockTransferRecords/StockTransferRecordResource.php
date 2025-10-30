<?php

namespace App\Filament\Resources\Purchase\StockTransferRecords;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Purchase\StockTransferRecord;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Purchase\StockTransferRecords\Pages\EditStockTransferRecord;
use App\Filament\Resources\Purchase\StockTransferRecords\Pages\ListStockTransferRecords;
use App\Filament\Resources\Purchase\StockTransferRecords\Pages\CreateStockTransferRecord;
use App\Filament\Resources\Purchase\StockTransferRecords\Schemas\StockTransferRecordForm;
use App\Filament\Resources\Purchase\StockTransferRecords\Tables\StockTransferRecordsTable;

class StockTransferRecordResource extends Resource
{

    use NavigationGroup;
    protected static ?string $model = StockTransferRecord::class;

    // protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-arrow-up';
    }

    protected static ?string $recordTitleAttribute = 'str_number';

    public static function form(Schema $schema): Schema
    {
        return StockTransferRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTransferRecordsTable::configure($table);
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
            'index' => ListStockTransferRecords::route('/'),
            'create' => CreateStockTransferRecord::route('/create'),
            'edit' => EditStockTransferRecord::route('/{record}/edit'),
        ];
    }
}
