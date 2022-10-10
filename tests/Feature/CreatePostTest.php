<?php

namespace asciito\BlogPackage\Tests\Feature;

use asciito\BlogPackage\Listeners\UpdatePostTitle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use asciito\BlogPackage\Tests\TestCase;
use asciito\BlogPackage\Models\Post;
use asciito\BlogPackage\Tests\User;
use Illuminate\Support\Facades\Event;
use asciito\BlogPackage\Events\PostWasCreated;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_create_a_post()
    {
        Event::fake();

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

    /** @test */
    public function a_post_requires_a_title_and_a_body()
    {
        $author = User::factory()->create();

        $this->actingAs($author)->post(route('posts.store'), [
            'title' => '',
            'body' => 'Some valid body',
        ])->assertSessionHasErrors('title');

        $this->actingAs($author)->post(route('posts.store'), [
            'title' => 'Some valid title',
            'body' => '',
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    public function guest_can_not_create_a_post()
    {
        // We're starting from an authenticated state
        $this->assertFalse(auth()->check());

        $this->post(route('posts.store'), [
            'title' => 'Some random title',
            'body' => 'Some random body',
        ])->assertForbidden();
    }

    /** @test */
    public function all_post_are_shown_via_the_index_route()
    {
        $this->withoutExceptionHandling();
        Post::factory()->create([
            'title' => 'Post number 1',
        ]);
        Post::factory()->create([
            'title' => 'Post number 2',
        ]);
        Post::factory()->create([
            'title' => 'Post number 3',
        ]);

        $this->get(route('posts.index'))
            ->assertSee('Post number 1')
            ->assertSee('Post number 2')
            ->assertSee('Post number 3')
            ->assertDontSee('Post number 4');
    }

    /** @test */
    public function a_single_post_is_shown_via_the_show_route()
    {
        $this->withoutExceptionHandling();
        $post = Post::factory()->create([
            'title' => 'The single post title',
            'body' => 'The single post body',
        ]);

        $this->get(route('posts.show', $post))
            ->assertSee('The single post title')
            ->assertSee('The single post body');
    }

    /** @test */
    public function an_event_is_emitted_whean_a_new_post_is_created()
    {
        Event::fake();

        $author = User::factory()->create();

        $this->actingAs($author)->post(route('posts.store'), [
           'title' => 'A valid title',
           'body' => 'A valid body',
        ]);

        $post = Post::first();

        Event::assertDispatched(PostWasCreated::class, function($event) use ($post) {
            return $event->post->id === $post->id;
        });
    }


    /** @test */
    public function a_newly_created_posts_title_will_be_changed()
    {
        $post = Post::factory()->create([
            'title' => 'Initial title',
        ]);

        $this->assertEquals('Initial title', $post->title);

        (new UpdatePostTitle())->handle(
            new PostWasCreated($post)
        );

        $this->assertEquals('New: Initial title', $post->title);
    }

    /** @test */
    public function the_title_of_a_post_is_updated_whenever_a_post_is_created()
    {
        $author = User::factory()->create();

        $this->actingAs($author)->post(route('posts.store'), [
            'title' => 'A valid title',
            'body' => 'A valid body',
        ]);

        $post = Post::first();

        $this->assertEquals('New: A valid title', $post->title);
    }
}