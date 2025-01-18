<?php

namespace App\Filament\Resources\Blog\ArticleResource\Pages;

use App\Filament\Resources\Blog\ArticleResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!Auth::user()->can('select_author_blog::article')) {
            $data['user_id'] = Auth::id();
        }

        $record->update($data);

        return $record;
    }

    protected function afterSave(): void
    {
        $authorName = User::find($this->record->user_id)?->name;
        $this->record->seo['author'] = $authorName;
        $this->record->save();
    }
}
