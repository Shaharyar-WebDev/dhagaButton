<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;

class EditDeliveryOrder extends EditRecord
{
    protected static string $resource = DeliveryOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
