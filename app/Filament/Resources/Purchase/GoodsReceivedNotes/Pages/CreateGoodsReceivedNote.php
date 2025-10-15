<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGoodsReceivedNote extends CreateRecord
{
    protected static string $resource = GoodsReceivedNoteResource::class;
}
