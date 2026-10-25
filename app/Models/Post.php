<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'excerpt', 'body', 'cover'];

    protected function casts(): array
    {
        return [
            'cover' => 'array',
        ];
    }
}
