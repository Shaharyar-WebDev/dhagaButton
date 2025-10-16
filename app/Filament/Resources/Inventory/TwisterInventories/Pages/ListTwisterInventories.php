<?php

namespace App\Filament\Resources\Inventory\TwisterInventories\Pages;

use App\Filament\Resources\Inventory\TwisterInventories\TwisterInventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTwisterInventories extends ListRecords
{
    protected static string $resource = TwisterInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
