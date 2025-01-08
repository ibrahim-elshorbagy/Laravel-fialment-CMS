<?php

namespace App\Filament\Resources\Blog\ArticleResource\Pages;

use App\Filament\Resources\Blog\ArticleResource;
use App\Models\Blog\Article;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListArticles extends ListRecords
{
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
}
