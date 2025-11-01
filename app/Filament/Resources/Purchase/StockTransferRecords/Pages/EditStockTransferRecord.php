<?php

namespace App\Filament\Resources\Purchase\StockTransferRecords\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Actions\CustomAction;
use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;

class EditStockTransferRecord extends EditRecord
{
    protected static string $resource = StockTransferRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            CustomAction::verifyStatus(),
            // CustomAction::unlock(),
        ];
    }
}
