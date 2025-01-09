<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Article as ArticleModel;
use Livewire\Component;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Article extends Component
{


    public ArticleModel $article;

    public function mount(ArticleModel $article)
    {
        if (!$this->article->is_published) {
            return redirect()->route('welcome');
        }
        $this->article = $article->load(['tags', 'categories']);

    }

    public function render()
    {

        return view('livewire.blog.article')->layout('layouts.app');

    }
}
