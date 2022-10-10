<?php

namespace asciito\BlogPackage\Listeners;

use asciito\BlogPackage\Events\PostWasCreated;

class UpdatePostTitle
{
    public function handle(PostWasCreated $event)
    {
        $event->post->update([
            'title' => 'New: ' . $event->post->title,
        ]);
    }
}