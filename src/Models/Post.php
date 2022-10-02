<?php

namespace asciito\BlogPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Disable laravel's masive assignment protection
    protected $guarded = [];

    public function author()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return \asciito\BlogPackage\Database\Factories\PostFactory::new();
    }
}