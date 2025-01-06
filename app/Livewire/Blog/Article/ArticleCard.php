<?php

namespace App\Livewire\Blog\Article;

use App\Models\Blog\Article as ArticleModel;
use Livewire\Component;
use Livewire\Attributes\Computed;

class ArticleCard extends Component
{

    #[Computed()]
    public function articles()
    {
        return  ArticleModel::limit(6)->get();
    }
    public function render()
    {
        return view('livewire.blog.article.article-card');
    }
}