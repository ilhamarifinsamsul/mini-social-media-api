<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{
    // function store
    public function store(Request $request) {
        // validasi
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'post_id' => 'required',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        // jika berhasil simpan di database
        $like = Like::create($data);

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
