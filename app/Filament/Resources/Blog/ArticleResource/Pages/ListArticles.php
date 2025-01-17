<?php

namespace App\Filament\Resources\Blog\ArticleResource\Pages;

use App\Filament\Resources\Blog\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class ListArticles extends ListRecords
{
        use HasPreviewModal;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {

        return [
                'All' => Tab::make('All'),
                'Published' => Tab::make('Published')
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', true))
                    ->badge(Article::query()->where('is_published', true)->count()),

                'Darft' => Tab::make('Darft')
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', false))
                    ->badge(Article::query()->where('is_published', false)->count()),

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
