<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;

class ViewGoodsReceivedNote extends ViewRecord
{
    protected static string $resource = GoodsReceivedNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            CustomAction::verifyStatus(),
        ];
    }
}
