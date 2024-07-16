<?php

namespace App\Services\Account;

use App\Enums\TokenAbility;
use App\Http\Requests\Account\LoginRequest;
use App\Http\Requests\Account\RegistrationRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AccountService
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return response()->json(['message' => 'authorization failed'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->createToken(Auth::user());
    }

    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        return $this->createToken($user);
    }
    public function refresh(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());

        $user = $token->tokenable;

        return $this->createToken($user);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
    }

    public function createToken(User $user)
    {
        $user->tokens()->delete();

        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

        return response()->json([
            'accessToken' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
        ], Response::HTTP_OK);
    }
}
