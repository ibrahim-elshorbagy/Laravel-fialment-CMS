<?php

namespace App\Filament\Resources\Blog\ArticleResource\Pages;

use App\Filament\Resources\Blog\ArticleResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
    use Illuminate\Database\Eloquent\Model;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        if (!Auth::user()->can('select_author_blog::article')) {
            $data['user_id'] = Auth::id();
        }

        return static::getModel()::create($data);
    }

    protected function afterCreate(): void
    {
        $authorName = User::find($this->record->user_id)?->name;
        $this->record->seo['author'] = $authorName;
        $this->record->save();
    }

}
