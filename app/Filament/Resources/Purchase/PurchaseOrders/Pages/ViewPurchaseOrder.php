<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\PurchaseOrders\PurchaseOrderResource;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CustomAction::createDeliveryOrder(),
        ];
    }
}
