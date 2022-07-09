<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth Route

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);


// Middleware Auth
Route::middleware(['auth:sanctum'])->group(function () {

    //  Logout
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']); 

    // Get /user
    Route::get('/user', function (Request $request) {
        
        return response()->json([
            'success'   => true,
            'message'   => 'Data Berhasil di Ambil !',
            'data'      => $request->user()
        ], 200);

    });

});