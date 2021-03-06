<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|min:3|max:24|string',
            'last_name' => 'required|min:3|max:24|string',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|min:4|max:24|alpha_num|unique:users,username',
            'password' => 'required|min:4|string'
        ]);
        $fields['password'] = bcrypt($fields['password']);
        $created_user = User::create($fields);
        $created_user_token = $created_user->createToken('some_secret_text')->plainTextToken;

        $response = ['user' => $created_user, 'access_token' => $created_user_token];

        return Response($response, 201);
    }




    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('username', $request['username'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response(1, 204);
    }
}
