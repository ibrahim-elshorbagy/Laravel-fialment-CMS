<?php

namespace App\Models\Blog;

use App\Models\Classification\Category;
use App\Models\Classification\Tag ;
use App\Models\User;
use Awcodes\Curator\Models\Media;
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
        return 'slug';
    }

    public function image(){
        return $this->belongsTo(Media::class, 'media_id');
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
