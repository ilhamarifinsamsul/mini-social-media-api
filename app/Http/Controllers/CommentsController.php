<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsController extends Controller
{
    // Function Store
    public function store(Request $request){
        // Comment berdasarkan request userId
        $user = JWTAuth::parseToken()->authenticate();
        $data = $request->all();
        $validator = Validator::make($data, [
            'post_id' => 'required',
            'content' => 'required|string|max:255',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        // jika berhasil simpan di database
        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $request->post_id,
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment
        ], 200);

    }

    // Function destroy
    public function destroy(int $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
            'data' => $comment
        ], 200);
    }

}
