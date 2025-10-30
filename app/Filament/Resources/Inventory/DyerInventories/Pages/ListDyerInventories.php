<?php

namespace App\Filament\Resources\Inventory\DyerInventories\Pages;

use App\Filament\Resources\Inventory\DyerInventories\DyerInventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDyerInventories extends ListRecords
{
    protected static string $resource = DyerInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
