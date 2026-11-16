<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::orderByDesc('published_at')->orderByDesc('id')->get()]);
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true, 'published_at' => now()])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Post published.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request));

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:20000'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? now();

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $n = 2;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }
}
