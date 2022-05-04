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
    $user = User::where(['email' => $githubUser->email, 'login_provider' => 'github'])->first();
    if(!$user){
        $user = User::create([
            'email' => $githubUser->email,
            'name' => $githubUser->name || $githubUser->nickname,
            'avatar' => $githubUser->avatar,
            'login_provider' => 'github'
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
    $user = User::where(['email' => $facebookUser->email, 'login_provider' => 'facebook'])->first();
    if(!$user){
        $user = User::create([
            'email' => $facebookUser->email,
            'name' => $facebookUser->name,
            'avatar' => $facebookUser->avatar,
            'login_provider' => 'facebook'
        ]);
        $token = $user->createToken($user->name . "'s device")->plainTextToken;
    }else{
        $token = $user->createToken($user->name . "'s device")->plainTextToken;
    }
    return redirect(env('CLIENT_URL') . '/login' . '?token=' . $token);
});