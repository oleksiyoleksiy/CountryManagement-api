<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\LoginRequest;
use App\Http\Requests\Account\RegistrationRequest;
use App\Services\Account\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(private AccountService $service)
    {
    }

    public function login(LoginRequest $request)
    {
        return $this->service->login($request);
    }

    public function register(RegistrationRequest $request)
    {
        return $this->service->register($request);
    }

    public function refresh(Request $request)
    {
        return $this->service->refresh($request);
    }

    public function logout()
    {
        $this->service->logout();
        return response()->noContent();
    }
}
