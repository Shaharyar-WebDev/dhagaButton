<?php

namespace App\Filament\Resources\Master\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm());
    }
}
