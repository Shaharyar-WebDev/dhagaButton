<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoodsReceivedNotes extends ListRecords
{
    protected static string $resource = GoodsReceivedNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
