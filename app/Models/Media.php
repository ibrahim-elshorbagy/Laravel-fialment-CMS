<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];

    public function mediable()
    {
        return $this->morphTo();
    }
}
