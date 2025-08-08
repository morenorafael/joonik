<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::whereEmail($request->email)->firstOrFail();

        abort_unless(Hash::check($request->password, $user->password), 401);

        $token = $user->createToken('app', [
            'view-locations',
            'create-locations',
        ], now()->addWeek());

        return ['token' => $token->plainTextToken];
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('app', [
            'view-locations',
            'create-locations',
        ], now()->addWeek());

        return ['token' => $token->plainTextToken];
    }
}
