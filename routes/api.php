<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });
    
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:sanctum'
], function(){
    Route::apiResource('boards', BoardController::class);
    Route::apiResource('listings', ListingController::class);
    Route::apiResource('cards', CardController::class);
    Route::post('cards/{id}/move', [CardController::class, 'move']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::resource('checklist-items', ChecklistItemController::class);
    Route::fallback(function(){
        return response()->json(['message' => 'Route not found!'], 404);
    });
});