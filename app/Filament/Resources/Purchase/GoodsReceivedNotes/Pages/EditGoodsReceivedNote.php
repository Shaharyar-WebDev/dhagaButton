<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGoodsReceivedNote extends EditRecord
{
    protected static string $resource = GoodsReceivedNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
