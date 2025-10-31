<?php

namespace App\Filament\Resources\Master\Articles\Pages;

use App\Filament\Resources\Master\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
}
