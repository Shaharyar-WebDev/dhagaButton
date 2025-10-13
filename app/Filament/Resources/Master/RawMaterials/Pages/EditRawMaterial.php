<?php

namespace App\Filament\Resources\Master\RawMaterials\Pages;

use App\Filament\Resources\Master\RawMaterials\RawMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRawMaterial extends EditRecord
{
    protected static string $resource = RawMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
