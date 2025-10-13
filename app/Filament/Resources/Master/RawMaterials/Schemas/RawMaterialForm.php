<?php

namespace App\Filament\Resources\Master\RawMaterials\Schemas;

use App\Models\Master\Unit;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Master\RawMaterialType;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Filament\Resources\Master\Units\Schemas\UnitForm;

class RawMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Material Name')
                            // ->placeholder('e.g. Polyester Yarn')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('raw_material_type_id')
                            ->label('Material Type')
                            ->options(RawMaterialType::cachedOptions())
                            ->required()
                            ->placeholder('Select material type')
                            ->helperText('E.g. Yarn, Cone, Label, Box, etc.'),

                        Select::make('unit_id')
                            ->label('Unit of Measure')
                            ->options(Unit::cachedOptions())
                            ->relationship('unit', 'name')
                            ->manageOptionForm(UnitForm::getForm())
                            // ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select unit')
                            ->helperText('Define the unit in which this raw material is tracked'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
