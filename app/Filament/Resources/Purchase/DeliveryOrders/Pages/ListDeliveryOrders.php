<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Pages;

use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryOrders extends ListRecords
{
    protected static string $resource = DeliveryOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
