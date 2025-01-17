<?php

namespace App\Models;

use App\Models\Tag ;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use WireComments\Traits\Commentable;


class Article extends Model
{
    protected $guarded = ['id'];

    use HasSEO;
    use Commentable;

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            image: $this->image ? url(Storage::url($this->image->path)) : null,
        );
    }



    public function getRouteKeyName()
    {
        return request()->is('dashboard/*') ? 'id' : 'slug';
    }

    public function media(){
        return $this->morphOne(Media::class, 'mediable');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
