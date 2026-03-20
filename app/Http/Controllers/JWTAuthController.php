<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthController extends Controller
{
    // handling register
    public function register(Request $request){
        // validasi
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
        // jika gagal
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // jika berhasil
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // retur response token jwt
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }

    // handling login
    public function login(Request $request){
        // validasi
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // panggil request email dan password
        $credentials = $request->only('email', 'password');

        try {
            // jika gagal
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // jika token gagal di buat
            return response()->json([
                'success' => false,
                'error' => 'could_not_create_token'], 500);
        }

        // jika berhasil
        return response()->json([
            'success' => true,
            'message' => 'Login Success',
            'token' => $token
        ], 200);
    }

}
