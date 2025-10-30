<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Purchase\GoodsReceivedNote;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages\EditGoodsReceivedNote;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages\ViewGoodsReceivedNote;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages\ListGoodsReceivedNotes;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Pages\CreateGoodsReceivedNote;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Schemas\GoodsReceivedNoteForm;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Tables\GoodsReceivedNotesTable;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\Schemas\GoodsReceivedNoteInfolist;

class GoodsReceivedNoteResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = GoodsReceivedNote::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return "heroicon-o-receipt-refund";
    }


    protected static ?string $recordTitleAttribute = 'grn_number';

    public static function form(Schema $schema): Schema
    {
        return GoodsReceivedNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GoodsReceivedNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsReceivedNotesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGoodsReceivedNotes::route('/'),
            'create' => CreateGoodsReceivedNote::route('/create'),
            // 'view' => ViewGoodsReceivedNote::route('/{record}'),
            'edit' => EditGoodsReceivedNote::route('/{record}/edit'),
        ];
    }
}
