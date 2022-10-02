<?php

namespace asciito\BlogPackage\Traits;

use asciito\BlogPackage\Models\Post;

trait HasPosts
{
    public function posts()
    {
        return $this->morphMany(Post::class, 'author');
    }
}