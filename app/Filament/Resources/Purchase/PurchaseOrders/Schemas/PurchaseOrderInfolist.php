<?php

namespace App\Filament\Resources\Purchase\PurchaseOrders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PurchaseOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('po_number'),
                TextEntry::make('supplier_id')
                    ->numeric(),
                TextEntry::make('raw_material_id')
                    ->numeric(),
                TextEntry::make('ordered_quantity')
                    ->numeric(),
                TextEntry::make('rate')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('remarks')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('expected_delivery_date')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
