<?php

namespace App\Filament\Resources\Purchase\StockTransferRecords\Pages;

use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockTransferRecords extends ListRecords
{
    protected static string $resource = StockTransferRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
