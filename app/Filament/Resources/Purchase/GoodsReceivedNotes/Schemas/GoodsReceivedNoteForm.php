<?php

namespace App\Filament\Resources\Purchase\GoodsReceivedNotes\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use App\Models\Purchase\PurchaseOrder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Master\Brands\Schemas\BrandForm;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class GoodsReceivedNoteForm
{
    public static function getForm()
    {
        return [
            Section::make()->columnSpanFull()->schema([

            ]),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm());
    }
}
