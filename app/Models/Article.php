<?php

namespace App\Models;

use App\Models\Tag ;
use App\Models\User;
use Carbon\Carbon;
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

        //============= Methods ==========================

    public function scopePublished($query){
        $query->where('is_published',true);
    }

    public function scopeFeatured($query){
        $query->where('featured',true);
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('title', 'like', "%{$search}%");
    }

    public function scopePopular($query){
        $query->withCount('likes')->orderBy('likes_count','desc');
    }

    public function scopeWithCategory($query,string $category){
            $query->whereHas('categories', function ($query) use($category){
                $query->where('slug', $category);
            });
        }

    public function getThumbnailUrl()
    {
        if ($this->media && $this->media->path && !empty($this->media->path)) {
            $isUrl = str_contains($this->media->path, 'http');
            return $isUrl
                ? $this->media->path
                : Storage::disk('public')->url($this->media->path);
        }

        return 'https://placehold.co/640x480?text='.str_replace(' ', '+', $this->title);
    }
}
