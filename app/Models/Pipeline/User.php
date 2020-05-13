<?php

namespace App\Models\Pipeline;

use App\Models\BaseModel;

class User extends BaseModel
{
    protected $table = 'pipeline_users';

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
