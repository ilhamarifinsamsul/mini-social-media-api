<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LikesController extends Controller
{
    // function store
    public function store(Request $request) {
        // Comment berdasarkan request userId
        $user = JWTAuth::parseToken()->authenticate();
        // validasi
        $data = $request->all();
        $validator = Validator::make($data, [
            'post_id' => 'required|exists:posts,id',
        ],
        [
            'post_id.exists' => 'Post not found'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        // jika berhasil simpan di database
        $like = Like::create([
            'user_id' => $user->id,
            'post_id' => $request->post_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Like created successfully',
            'data' => $like
        ], 200);
    }

    // function destroy
    public function destroy(int $id) {
        $like = Like::findOrFail($id);
        $like->delete();

        return response()->json([
            'message' => 'Like deleted successfully',
            'data' => $like
        ], 200);
    }
}
