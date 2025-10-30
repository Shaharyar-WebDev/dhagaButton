<?php

namespace App\Filament\Resources\Inventory\DyerInventories\Pages;

use App\Filament\Resources\Inventory\DyerInventories\DyerInventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDyerInventory extends EditRecord
{
    protected static string $resource = DyerInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
