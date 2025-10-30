<?php

namespace App\Filament\Resources\Accounting\SupplierLedgers\Pages;

use App\Filament\Resources\Accounting\SupplierLedgers\SupplierLedgerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplierLedger extends EditRecord
{
    protected static string $resource = SupplierLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
