<?php

namespace App\Filament\Resources\Purchase\DyeingPlans\Schemas;

use App\Models\Master\Unit;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Master\Units\Schemas\UnitForm;
use App\Filament\Resources\Master\Shades\Schemas\ShadeForm;
use App\Filament\Resources\Master\Suppliers\Schemas\SupplierForm;

class DyeingPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Details')
                    ->schema([
                        Select::make('dyer_id')
                            ->label('Dyer')
                            ->manageOptionForm(SupplierForm::getForm())
                            ->relationship('dyer', 'name')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                // Section::make('Plan Items')
                //     ->schema([
                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Grid::make()
                            ->columns(function ($get) {
                                return $get('../../id') ? 5 : 3;
                            })
                            ->schema([
                                Select::make('shade_id')
                                    ->label('Shade')
                                    ->relationship('shade', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->manageOptionForm(ShadeForm::getForm())
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->label('Quantity')
                                    ->suffix(fn() => Unit::where('name', 'Bundle')->first()->symbol)
                                    ->required(),

                                // Select::make('unit_id')
                                //     ->label('Unit')
                                //     ->relationship('unit', 'name')
                                //     ->manageOptionForm(UnitForm::getForm())
                                //     ->default(fn() => Unit::where('name', 'Bundle')->first()->id)
                                //     ->searchable()
                                //     ->disabled()
                                //     ->dehydrated()
                                //     ->preload()
                                //     ->required(),

                                DatePicker::make('date')
                                    ->required()
                                    ->default(now()),

                                // 🧪 QC Status
                                Select::make('qc_status')
                                    ->label('QC Status')
                                    ->options([
                                        'qc_pending' => 'QC Pending',
                                        'rework' => 'Rework',
                                        'approved' => 'Approved',
                                    ])
                                    ->default('qc_pending')
                                    ->required()
                                    ->visible(fn($get) => $get('../../id'))
                                    ->native(false),

                                // 📅 QC Date
                                DatePicker::make('qc_date')
                                    ->label('QC Date')
                                    // ->native(false)
                                    ->displayFormat('d M Y')
                                    ->visible(fn($get) => $get('../../id'))
                                    ->placeholder('Select QC date'),

                            ]),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
                    ->addActionLabel('Add Item')
                    ->collapsible(),
                // ])
                // ->columnSpanFull(),
            ]);
    }
}
