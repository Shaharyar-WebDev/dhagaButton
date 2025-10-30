<?php

namespace App\Filament\Resources\Inventory\TwisterInventories\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Inventory\TwisterInventories\TwisterInventoryResource;

class ListTwisterInventories extends ListRecords
{
    protected static string $resource = TwisterInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            CustomAction::receiveYarn(),
            CustomAction::sendToDyer(),
        ];
    }
}
