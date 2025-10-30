<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Purchase\PurchaseOrder;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Purchase\PurchaseOrders\Pages\EditPurchaseOrder;
use App\Filament\Resources\Purchase\PurchaseOrders\Pages\ViewPurchaseOrder;
use App\Filament\Resources\Purchase\PurchaseOrders\Pages\ListPurchaseOrders;
use App\Filament\Resources\Purchase\PurchaseOrders\Pages\CreatePurchaseOrder;
use App\Filament\Resources\Purchase\PurchaseOrders\Schemas\PurchaseOrderForm;
use App\Filament\Resources\Purchase\PurchaseOrders\Tables\PurchaseOrdersTable;
use App\Filament\Resources\Purchase\PurchaseOrders\Schemas\PurchaseOrderInfolist;

class PurchaseOrderResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = PurchaseOrder::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return "heroicon-o-document-text";
    }

    protected static ?string $recordTitleAttribute = 'po_number';

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PurchaseOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['rawMaterial', 'rawMaterial.type']);
            // ->withSum('deliveryOrders', 'quantity')
            // ->withSum('verifiedDeliveryOrders', 'quantity');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            // 'view' => ViewPurchaseOrder::route('/{record}'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
