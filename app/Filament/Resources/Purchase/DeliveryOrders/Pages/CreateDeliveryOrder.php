<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Pages;

use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeliveryOrder extends CreateRecord
{
    protected static string $resource = DeliveryOrderResource::class;

    protected static bool $canCreateAnother = false;
}
