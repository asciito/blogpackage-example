<?php

namespace asciito\BlogPackage\Http\Controllers;

use asciito\BlogPackage\Events\PostWasCreated;
use asciito\BlogPackage\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return view('blogpackage::posts.index', compact('posts'));
    }

    public function show()
    {
         $post = Post::findOrFail(request('post'));

         return view('blogpackage::posts.show', compact('post'));
    }

    public function store()
    {
        if (! auth()->check()) {
            abort(403, 'Only authenticated users can create new posts');
        }

        request()->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $author = auth()->user();

        $post = $author->posts()->create([
            'title' => request('title'),
            'body' => request('body'),
        ]);

        event(new PostWasCreated($post));

        return redirect(route('posts.show', $post));
    }
}
