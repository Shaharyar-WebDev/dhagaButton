<?php

namespace App\Filament\Resources\Master\Shades\Schemas;

use App\Filament\Resources\Master\Articles\Schemas\ArticleForm;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ShadeForm
{
    public static function getForm()
    {
        return [
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    Select::make('article_id')
                        ->label('Article')
                        ->relationship('article', 'name')
                        ->manageOptionForm(ArticleForm::getForm())
                        ->required()
                ])->columnSpanFull(),
        ];
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm());
    }
}
