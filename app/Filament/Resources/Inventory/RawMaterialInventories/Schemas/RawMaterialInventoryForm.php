<?php

namespace App\Filament\Resources\Inventory\RawMaterialInventories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RawMaterialInventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('raw_material_id')
                    ->required()
                    ->numeric(),
                TextInput::make('brand_id')
                    ->required()
                    ->numeric(),
                TextInput::make('in_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('out_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('balance')
                    ->required()
                    ->numeric(),
                TextInput::make('rate')
                    ->numeric()
                    ->default(null),
                TextInput::make('value')
                    ->numeric()
                    ->default(null),
                TextInput::make('reference_type')
                    ->default(null),
                TextInput::make('reference_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('remarks')
                    ->default(null),
            ]);
    }
}
