<?php

namespace App\Models\Classification;

use App\Models\Blog\Article;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }



    public function articles() {
        return $this->morphedByMany(Article::class, 'categoryable');
    }


}
