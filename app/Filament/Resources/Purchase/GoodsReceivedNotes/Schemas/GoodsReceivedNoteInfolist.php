<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GoodsReceivedNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('grn_number'),
                TextEntry::make('purchase_order_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('supplier_id')
                    ->numeric(),
                TextEntry::make('reference_number')
                    ->placeholder('-'),
                TextEntry::make('received_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('remarks')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
