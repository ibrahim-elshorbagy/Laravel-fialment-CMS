<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Article as ArticleModel;
use Livewire\Component;

class Article extends Component
{


    public ArticleModel $article;
    public function render()
    {
        return view('livewire.blog.article')->layout('layouts.app');
    }
}
