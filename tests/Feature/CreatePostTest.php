<?php

namespace asciito\BlogPackage\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use asciito\BlogPackage\Tests\TestCase;
use asciito\BlogPackage\Models\Post;
use asciito\BlogPackage\Tests\User;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_create_a_post()
    {
        // Making sure there's no post already
        $this->assertCount(0, Post::all());

        $author = User::factory()->create();

        $response = $this->actingAs($author)->post(route('posts.store'), [
            'title' => 'My first fake title',
            'body' => 'My first fake body',
        ]);

        $this->assertCount(1, Post::all());

        tap(Post::first(), function(Post $post) use($author, $response) {
            $this->assertEquals('My first fake title', $post->title);
            $this->assertEquals('My first fake body', $post->body);
            $response->assertRedirect(route('posts.show', $post));
        });
    }
}