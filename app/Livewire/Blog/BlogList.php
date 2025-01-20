<?php

namespace App\Livewire\Blog;

use App\Models\Article;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BlogList extends Component
{
    use WithPagination;

    // URL query parameters
    #[Url()]
    public $sort = 'desc';

    #[Url()]
    public $search = '';

    #[Url()]
    public $category = '';

    #[Url()]
    public $popular = false;


    protected $rules = [
        'sort' => 'required|in:asc,desc',
        'search' => 'nullable|string|max:255',
        'category' => 'nullable|string|exists:categories,slug',
        'popular' => 'boolean',
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function setSort($sort)
    {
        $this->sort = $sort;
        $this->validateOnly('sort');
    }


    #[On('search')]
    public function updateSearch($search)
    {
        $this->search = $search;
        $this->validateOnly('search');

        $this->resetPage();
    }


    public function ClearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->popular = false;
        $this->sort = 'desc';

        $this->validate();

        $this->resetPage();
    }


    #[Computed()]
    public function articles()
    {
        return Article::published()
            ->with('user', 'categories', 'media')
            ->when($this->activeCategory, function ($query) {
                $query->withCategory($this->category);
            })
            // ->when($this->popular, function($query) { $query->popular(); })

            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->orderBy('published_at', $this->sort)
            ->paginate(5);
    }


    #[Computed()]
    public function activeCategory()
    {
        if ($this->category === null || $this->category === '') {
            return null;
        }
        return Category::where('slug', $this->category)->first();
    }


    #[Computed()]
    public function activeSearch()
    {
        if ($this->search === null || $this->search === '') {
            return null;
        }
        return Article::where('title', 'like', "%{$this->search}%")->first();
    }


    public function render()
    {
        return view('livewire.blog.blog-list', [
            'categories' => Category::query()->where('is_active', true)->get(),
        ])->layout('layouts.app');
    }
}
