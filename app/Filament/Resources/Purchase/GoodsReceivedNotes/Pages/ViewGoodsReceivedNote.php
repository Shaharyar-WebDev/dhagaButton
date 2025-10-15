<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGoodsReceivedNote extends ViewRecord
{
    protected static string $resource = GoodsReceivedNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
