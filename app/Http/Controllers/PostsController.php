<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostsController extends Controller
{
    // Function index
    public function index() {
        $posts = Post::with('user', 'comments', 'likes')->get();

        return response()->json([
            'data' => $posts,
            'success' => true
        ]);
    }

    // Function store
    public function store(Request $request) {
        // create post berdasarkan request userId
        $user = JWTAuth::parseToken()->authenticate();

        // Validasi
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'image_url' => 'nullable'
        ]);
        // jika validasi tidak sesuai
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'success' => false
            ], 400);
        }
        // jika berhasil simpan ke database
        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'image_url' => $request->image_url
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }

    // Function show
    public function show(int $id) {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $post,
        ], 200);
    }

    // Function update
    public function update(int $id, Request $request) {
        // Validasi
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'image_url' => 'nullable'
        ]);
        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'success' => false
            ], 422);
        }
        // jika berhasil simpan ke database
        $post = Post::findOrFail($id);
        $post->update([
            'content' => $request->content,
            'image_url' => $request->image_url
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }

    // Function destroy
    public function destroy(int $id) {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ], 200);

    }
}
