<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect(); 
});

Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();
    $user = User::where(['email' => $githubUser->email])->first();
    if(!$user){
        $user = User::create([
            'email' => $githubUser->email,
            'name' => $githubUser->name || $githubUser->nickname,
            'avatar' => $githubUser->avatar,
        ]);
        $token = $user->createToken($user->name . "'s device")->plainTextToken;
    }else{
        $token = $user->createToken($user->name . "'s device")->plainTextToken;
    }
    return redirect(env('CLIENT_URL') . '/login' . '?token=' . $token);
});

Route::get('/auth/facebook/redirect', function () {
    return Socialite::driver('facebook')->redirect(); 
});

Route::get('/auth/facebook/callback', function () {
    $facebookUser = Socialite::driver('facebook')->user();
    $user = User::where(['email' => $facebookUser->email])->first();
    if(!$user){
        $user = User::create([
            'email' => $facebookUser->email,
            'name' => $facebookUser->name || $facebookUser,
            'avatar' => $facebookUser->avatar,
        ]);
        $token = $user->createToken($user->name . "'s device")->plainTextToken;
    }else{
        $token = $user->createToken($user->name . "'s device")->plainTextToken;
    }
    return redirect(env('CLIENT_URL') . '/login' . '?token=' . $token);
});