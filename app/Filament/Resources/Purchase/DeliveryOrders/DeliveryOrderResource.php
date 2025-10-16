<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders;

use App\Filament\Resources\Purchase\DeliveryOrders\Pages\CreateDeliveryOrder;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\EditDeliveryOrder;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\ListDeliveryOrders;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\ViewDeliveryOrder;
use App\Filament\Resources\Purchase\DeliveryOrders\Schemas\DeliveryOrderForm;
use App\Filament\Resources\Purchase\DeliveryOrders\Schemas\DeliveryOrderInfolist;
use App\Filament\Resources\Purchase\DeliveryOrders\Tables\DeliveryOrdersTable;
use App\Models\Purchase\DeliveryOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryOrderResource extends Resource
{
    protected static ?string $model = DeliveryOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'do_number';

    public static function form(Schema $schema): Schema
    {
        return DeliveryOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DeliveryOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryOrdersTable::configure($table);
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
            'index' => ListDeliveryOrders::route('/'),
            'create' => CreateDeliveryOrder::route('/create'),
            'view' => ViewDeliveryOrder::route('/{record}'),
            'edit' => EditDeliveryOrder::route('/{record}/edit'),
        ];
    }
}
