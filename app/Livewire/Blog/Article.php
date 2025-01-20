<?php

namespace App\Livewire\Blog;

use App\Models\Article as ArticleModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Article extends Component
{


    public ?ArticleModel $article = null;

    public function mount(?ArticleModel $article)
    {
        if ( !$article || (!$article->is_published && $article->user_id !== auth()->id())) {
            return redirect()->route('welcome');
        }

        $this->article = $article->load(['tags', 'categories']);
    }


    public function render()
    {

        return view('livewire.blog.article')->layout('layouts.app');

    }
}
