<?php

namespace App\Filament\Resources\Purchase\DeliveryOrders\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;

class ViewDeliveryOrder extends ViewRecord
{
    protected static string $resource = DeliveryOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CustomAction::verifyDo(),
        ];
    }
}
