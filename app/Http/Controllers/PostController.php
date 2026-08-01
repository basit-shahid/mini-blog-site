<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category', 'user')->published()->latest('published_at')->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load('category', 'tags', 'user');
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create', [
            'categories' => Category::all(),
            'tags'       => Tag::all(),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);
        $post = $request->user()->posts()->create($request->validated());
        $post->tags()->sync($request->input('tags', []));
        return redirect()->route('posts.show', $post);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $post->update($request->validated());
        $post->tags()->sync($request->input('tags', []));
        return redirect()->route('posts.show', $post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index');
    }
}