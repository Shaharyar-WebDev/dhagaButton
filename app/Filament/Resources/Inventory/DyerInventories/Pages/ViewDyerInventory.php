<?php

namespace App\Filament\Resources\Inventory\DyerInventories\Pages;

use App\Filament\Resources\Inventory\DyerInventories\DyerInventoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDyerInventory extends ViewRecord
{
    protected static string $resource = DyerInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
