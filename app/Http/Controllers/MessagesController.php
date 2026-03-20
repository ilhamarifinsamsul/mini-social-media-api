<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessagesController extends Controller
{
    // function store
    public function store(Request $request){
        // Comment berdasarkan request userId
        $user = JWTAuth::parseToken()->authenticate();
        $data = $request->all();

        // validasi
        $validator = Validator::make($data, [
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
        ],
        [
            'receiver_id.exists' => 'Receiver not found'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        // jika berhasil simpan di database
        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'message_content' => $request->message_content
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message created successfully',
            'data' => $message
        ], 200);
    }

    public function show(int $id)
    {
        $message = Message::find($id);

        if (!$message) {
            return response()->json([
                "success" => false,
                "message" => "Message not found",
                "data" => null
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Message Detail successfully",
            "data" => $message
        ], 200);
    }

    // function getMessagesByUserId
    public function getMessagesByUserId(int $user_id) {
        $message = Message::where('receiver_id', $user_id)->get();

        if ($message->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "Message not found",
                "data" => []
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Message successfully By User ID",
            "data" => $message
        ], 200);
    }

    // function destroy
    public function destroy(int $id) {
        $message = Message::find($id);

        // jika data tidak ditemukan
        if (!$message) {
            return response()->json([
                "success" => false,
                "message" => "Message not found",
                "data" => null
            ], 404);
        }

        $message->delete();

        return response()->json([
            "success" => true,
            "message" => "Message deleted successfully",
            "data" => $message
        ], 200);
    }
}
