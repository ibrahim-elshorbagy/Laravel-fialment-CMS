<?php

namespace App\Models\Blog;

use App\Models\User;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Model;
use WireComments\Traits\Commentable;

class Article extends Model
{
    use Commentable;
    protected $guarded = ['id'];

    public function image(){
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
