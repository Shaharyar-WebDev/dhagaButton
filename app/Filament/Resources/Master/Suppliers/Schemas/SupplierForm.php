<?php

namespace App\Filament\Resources\Master\Suppliers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class SupplierForm
{
    public static function getForm()
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->label('Supplier Name')
                        // ->placeholder('e.g. Alpha Traders')
                        ->required()
                        ->unique(ignoreRecord: true)
                        // ->columnSpanFull()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email Address')
                        // ->placeholder('e.g. example@gmail.com')
                        ->email()
                        ->maxLength(255),

                    TextInput::make('contact')
                        ->label('Contact Number')
                        // ->placeholder('e.g. +92 300 1234567')
                        ->tel()
                        ->maxLength(255),

                    TextInput::make('agreed_upon_rate_per_unit')
                        ->label('Agreed Rate per Unit')
                        ->numeric()
                        ->prefix('PKR')
                        // ->placeholder('e.g. 250.00')
                        ->nullable()
                        ->helperText('This rate will be used as the default purchase rate for this supplier.'),

                    Textarea::make('address')
                        ->label('Address')
                        // ->placeholder('Street, City, Province')
                        ->columnSpanFull()
                        ->maxLength(255),
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
