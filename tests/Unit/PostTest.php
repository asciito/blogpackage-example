<?php

namespace asciito\BlogPackage\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use asciito\BlogPackage\Tests\TestCase;
use asciito\BlogPackage\Models\Post;
use asciito\BlogPackage\Tests\User;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_post_has_a_title()
    {
        $post = Post::factory()->create(['title' => 'Fake title']);
        $this->assertEquals('Fake title', $post->title);
    }

    /** @test */
    public function a_post_has_a_body()
    {
        $post = Post::factory()->create(['body' => 'Fake body']);
        $this->assertEquals('Fake body', $post->body);
    }

    /** @test */
    public function a_post_has_an_author()
    {
        $post = Post::factory()->create(['author_id' => 999]);
        $this->assertEquals(999, $post->author_id);
    }

    /** @test */
    public function a_post_has_an_athor_type()
    {
        $post = Post::factory()->create(['author_type' => 'Faker\User']);
        $this->assertEquals('Faker\User', $post->author_type);
    }

    /** @test */
    public function a_post_belongs_to_an_author()
    {
        $author = User::factory()->create();

        $author->posts()->create([
            'title' => 'My first fake post',
            'body' => 'The body of this fake post',
        ]);

        $this->assertCount(1, Post::all());
        $this->assertCount(1, $author->posts);

        tap($author->posts()->first(), function($post) use($author) {
            $this->assertEquals('My first fake post', $post->title);
            $this->assertEquals('The body of this fake post', $post->body);
            $this->assertTrue($post->author->is($author));
        });
    }
}