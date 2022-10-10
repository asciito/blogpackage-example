<?php

namespace asciito\BlogPackage\Providers;

use asciito\BlogPackage\Events\PostWasCreated;
use asciito\BlogPackage\Listeners\UpdatePostTitle;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostWasCreated::class => [
            UpdatePostTitle::class,
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}