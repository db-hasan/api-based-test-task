<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\Post;
use Exception;

class PostController extends Controller
{
    /* Create a new post.*/
    public function storePost(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $post = Post::create([
                'title'   => $request->title,
                'content' => $request->content,
            ]);

            return response()->json($post, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create post'], 500);
        }
    }

    /* List all posts.*/
    public function indexPost(): JsonResponse
    {
        $posts = Post::orderBy('id', 'desc')->get();
        return response()->json($posts);
    }


    /* View a single post by ID. */
    public function viewPost($id): JsonResponse
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        return response()->json($post);
    }
}
