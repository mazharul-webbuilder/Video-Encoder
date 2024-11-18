<?php

use App\Http\Controllers\VideoEncodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' =>'auth:sanctum'], function(){
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/start-video-encoding', [VideoEncodeController::class, 'startVideoEncoding']);
    Route::post('/start-bulk-video-encoding', [VideoEncodeController::class, 'startBulkVideoEncoding']);
    Route::get('/fetch-videos', [VideoEncodeController::class, 'fetchVideos']);
    Route::get('/encoding-video-list', [VideoEncodeController::class, 'encodingVideosList']);
    Route::get('/fetch-encode-video-progress-status/{id}', [VideoEncodeController::class, 'encodingVideoStatus']);
});
Route::get('/cache/{key}', function ($key) {
    dd(Cache::get($key));
});