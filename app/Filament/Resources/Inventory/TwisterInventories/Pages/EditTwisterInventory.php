<?php

namespace App\Filament\Resources\Inventory\TwisterInventories\Pages;

use App\Filament\Resources\Inventory\TwisterInventories\TwisterInventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTwisterInventory extends EditRecord
{
    protected static string $resource = TwisterInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
