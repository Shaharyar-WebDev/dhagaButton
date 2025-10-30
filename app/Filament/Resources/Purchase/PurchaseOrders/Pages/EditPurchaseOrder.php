<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\PurchaseOrders\PurchaseOrderResource;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CustomAction::createDeliveryOrder(),
            CustomAction::createGoodReceivedNote(),
            // ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
