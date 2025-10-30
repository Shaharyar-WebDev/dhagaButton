<?php

namespace App\Filament\Resources\Accounting\SupplierLedgers\Pages;

use App\Filament\Resources\Accounting\SupplierLedgers\SupplierLedgerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplierLedger extends ViewRecord
{
    protected static string $resource = SupplierLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
