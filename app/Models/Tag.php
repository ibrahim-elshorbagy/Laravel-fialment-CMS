<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];

    public function articles() {
        return $this->morphedByMany(Article::class, 'categoryable');
    }

}
