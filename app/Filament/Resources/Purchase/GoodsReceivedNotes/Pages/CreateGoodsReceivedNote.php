<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;

class CreateGoodsReceivedNote extends CreateRecord
{
    protected static string $resource = GoodsReceivedNoteResource::class;

    protected static bool $canCreateAnother = false;

}
