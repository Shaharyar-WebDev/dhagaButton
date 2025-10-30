<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Purchase\DeliveryOrder;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\EditDeliveryOrder;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\ViewDeliveryOrder;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\ListDeliveryOrders;
use App\Filament\Resources\Purchase\DeliveryOrders\Pages\CreateDeliveryOrder;
use App\Filament\Resources\Purchase\DeliveryOrders\Schemas\DeliveryOrderForm;
use App\Filament\Resources\Purchase\DeliveryOrders\Tables\DeliveryOrdersTable;
use App\Filament\Resources\Purchase\DeliveryOrders\Schemas\DeliveryOrderInfolist;

class DeliveryOrderResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = DeliveryOrder::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-clipboard-document-check';
    }

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
