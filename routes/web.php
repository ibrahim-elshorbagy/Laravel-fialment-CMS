<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Blog\Article;
use App\Livewire\Home;

Route::get('/',Home::class)->name('welcome');

Route::get('/blog/articles/{article:slug}',Article::class)->name('article.show');
