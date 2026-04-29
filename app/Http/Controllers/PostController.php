<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('user')->get();
    }

    public function store(Request $request)
    {
        return Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);
    }

    public function show($id)
    {
        return Post::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Nav atļauts'], 403);
        }

        $post->update($request->only('title', 'content'));

        return $post;
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Nav atļauts'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Deleted']);
    }
}