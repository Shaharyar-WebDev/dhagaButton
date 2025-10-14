<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders\Pages;

use App\Filament\Resources\Purchase\PurchaseOrders\PurchaseOrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
