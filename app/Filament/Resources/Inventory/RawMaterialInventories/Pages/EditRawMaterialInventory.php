<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories\Pages;

use App\Filament\Resources\Inventory\RawMaterialInventories\RawMaterialInventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRawMaterialInventory extends EditRecord
{
    protected static string $resource = RawMaterialInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
