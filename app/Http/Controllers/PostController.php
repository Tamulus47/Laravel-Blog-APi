<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\validator;
use App\Models\Post;
use App\Models\Tag;

class PostController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->posts()->orderByDesc('pinned')->get();

        return $posts->isEmpty() ? response()->json(['message' => 'No posts found'], 200) : response()->json($posts, 200);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array|min:1|exists:tags,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        $post = new Post([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $request->file('cover_image')->store('images'),
            'pinned' => $request->pinned,
        ]);

        $post->user_id = Auth::id();
        $post->save();

        $post->tags()->attach($request->tags);

        return response()->json(['message' => 'Post created successfully'], 200);
    }

    public function show(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validation = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array|min:1|exists:tags,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $request->file('cover_image') ? $request->file('cover_image')->store('images') : $post->cover_image,
            'pinned' => $request->pinned,
        ]);

        $post->tags()->sync($request->tags);

        return response()->json(['message' => 'Post updated successfully'], 200);
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function trashed()
    {
        $posts = Auth::user()->posts()->onlyTrashed()->get();
        return $posts->isEmpty() ? response()->json(['message' => 'Trash is empty'], 200) : response()->json($posts, 200);
    }

    public function restore($postId)
    {
        $post = Post::onlyTrashed()->where('id', $postId)->where('user_id', Auth::id())->first();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->restore();

        return response()->json(['message' => 'Post restored successfully']);
    }
}

