<?php

namespace App\Filament\Resources\Master\Articles;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Article;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Support\Traits\NavigationGroup;
use App\Filament\Resources\Master\Articles\Pages\EditArticle;
use App\Filament\Resources\Master\Articles\Pages\ListArticles;
use App\Filament\Resources\Master\Articles\Pages\CreateArticle;
use App\Filament\Resources\Master\Articles\Schemas\ArticleForm;
use App\Filament\Resources\Master\Articles\Tables\ArticlesTable;

class ArticleResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticlesTable::configure($table);
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
            'index' => ListArticles::route('/'),
            // 'create' => CreateArticle::route('/create'),
            // 'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
