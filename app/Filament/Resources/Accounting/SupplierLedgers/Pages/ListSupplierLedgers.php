<?php

namespace App\Filament\Resources\Accounting\SupplierLedgers\Pages;

use App\Filament\Resources\Accounting\SupplierLedgers\SupplierLedgerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplierLedgers extends ListRecords
{
    protected static string $resource = SupplierLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
