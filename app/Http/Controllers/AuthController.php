<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): AuthUserResource
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return new AuthUserResource($user, $user->createToken('auth_token')->plainTextToken);
    }

    public function login(LoginUserRequest $request): AuthUserResource
    {
        if (!Auth::attempt($request->validated())) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();

        return new AuthUserResource($user, $user->createToken('auth_token')->plainTextToken);
    }

    public function logout()
    {
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
