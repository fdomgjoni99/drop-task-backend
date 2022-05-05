<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
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
    // Route::apiResource('cards', CardController::class);
    Route::apiResource('boards', BoardController::class);
    Route::get('listings', [ListingController::class, 'index']);
    Route::delete('listings/{id}', [ListingController::class, 'destroy']);
    Route::delete('listings/{listingId}/cards/{id}/force-delete', [CardController::class, 'destroyPermanently']);
    Route::get('listings/{listingId}/cards/trash', [CardController::class, 'trashedCards']);
    Route::post('listings/{listingId}/cards/add', [CardController::class, 'add']);
    Route::post('listings/{listingId}/cards/remove', [CardController::class, 'remove']);
    Route::post('listings/{listingId}/cards/move', [CardController::class, 'move']);
    Route::apiResource('listings.cards', CardController::class)->only([
        'index', 'store', 'destroy'
    ]);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});