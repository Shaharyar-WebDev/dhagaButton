<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories\Pages;

use App\Filament\Resources\Inventory\RawMaterialInventories\RawMaterialInventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRawMaterialInventories extends ListRecords
{
    protected static string $resource = RawMaterialInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
