<?php

namespace App\Filament\Resources\Blog\ArticleResource\Pages;

use App\Filament\Resources\Blog\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditArticle extends EditRecord
{
        use HasPreviewModal;
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            PreviewAction::make(),

        ];
    }


    protected function getPreviewModalView(): ?string
    {
        return 'livewire.blog.article.article-preview';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'article';
    }
}
