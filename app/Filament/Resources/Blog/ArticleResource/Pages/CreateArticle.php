<?php

namespace App\Filament\Resources\Blog\ArticleResource\Pages;

use App\Filament\Resources\Blog\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
}
