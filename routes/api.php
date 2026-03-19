<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    // handle route post
    Route::prefix('posts')->group(function () {
     Route::get('/', [PostsController::class, 'index']); // Menampilkan semua data
     Route::post('/', [PostsController::class, 'store']); // Menambahkan data
     Route::get('/{id}', [PostsController::class, 'show']); // Menampilkan data berdasarkan id
     Route::put('/{id}', [PostsController::class, 'update']); // Mengupdate data
     Route::delete('/{id}', [PostsController::class, 'destroy']); // Menghapus data
    });

    // handle comments
    Route::prefix('comments')->group(function () {
        Route::post('/', [CommentsController::class, 'store']);
        Route::delete('/{id}', [CommentsController::class, 'destroy']);
    });

    // handle likes
    Route::prefix('likes')->group(function(){
        Route::post('/', [LikesController::class, 'store']);
        Route::delete('/{id}', [LikesController::class, 'destroy']);
    });
});

