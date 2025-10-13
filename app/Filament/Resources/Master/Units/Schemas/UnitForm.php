<?php

namespace App\Filament\Resources\Master\Units\Schemas;

use App\Models\Master\Unit;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;

class UnitForm
{
    public static function getForm(): array
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->label('Unit Name')
                        ->placeholder('e.g. Kilogram')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('symbol')
                        ->label('Symbol')
                        ->placeholder('e.g. kg')
                        ->maxLength(50),

                    Select::make('base_unit_id')
                        ->label('Base Unit')
                        ->relationship('baseUnit', 'name')
                        // ->helperText('Used when this unit is a derived version of another, e.g. Gram → Kilogram')
                        ->placeholder('Select base unit (if any)')
                        ->options(Unit::cachedOptions())
                        ->live()
                        // ->searchable()
                        ->preload(),

                    Fieldset::make('Conversion to Base Unit')
                        ->columns(2)
                        ->schema([
                            Select::make('conversion_operator')
                                ->label('Operator')
                                ->options([
                                    '*' => 'Multiply (*)',
                                    '/' => 'Divide (/)',
                                ])
                                ->native(false)
                                ->placeholder('Choose'),

                            TextInput::make('conversion_value')
                                ->label('Value')
                                ->numeric()
                                ->placeholder('e.g. 1000')
                                ->helperText('Value to multiply/divide by to convert to the base unit'),
                        ])
                        ->columnSpanFull()
                        ->visible(fn($get) => $get('base_unit_id') !== null),

                ])
                ->columns(2)
                ->columnSpanFull(),
        ];
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm());
    }
}
