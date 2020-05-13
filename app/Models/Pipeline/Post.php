<?php

namespace App\Models\Pipeline;

use App\Models\BaseModel;

class Post extends BaseModel
{
    protected $table = 'pipeline_posts';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
