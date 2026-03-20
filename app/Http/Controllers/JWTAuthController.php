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

}
