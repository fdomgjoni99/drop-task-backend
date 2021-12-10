<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|min:3|max:80',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:40',
            'password_confirmation' => 'required|same:password',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $token = $user->createToken('tokens');
        $user['token'] = $token->plainTextToken;
        return $user;
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:5'
        ]);
        if(!Auth::attempt($validated)){
            return response()->json(['error' => 'invalid credentials'], 401);
        }
        return ['token' => auth()->user()->createToken('tokens')->plainTextToken];
    }
}
