<?php

namespace App\Filament\Resources\Inventory\TwisterInventories\Pages;

use App\Filament\Resources\Inventory\TwisterInventories\TwisterInventoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTwisterInventory extends ViewRecord
{
    protected static string $resource = TwisterInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
