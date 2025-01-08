<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Blog\Article;
use App\Livewire\Home;
use App\Livewire\Plans\Plans;

Route::get('/Home',Home::class)->name('welcome');
Route::get('/plans',Plans::class)->name('plans');

Route::get('/blog/articles/{article:slug}',Article::class)->name('article.show');
