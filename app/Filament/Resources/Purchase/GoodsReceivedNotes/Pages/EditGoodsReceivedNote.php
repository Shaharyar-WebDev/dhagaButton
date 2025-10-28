<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;

class EditGoodsReceivedNote extends EditRecord
{
    protected static string $resource = GoodsReceivedNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            CustomAction::verifyStatus(),
            DeleteAction::make(),
        ];
    }
}
