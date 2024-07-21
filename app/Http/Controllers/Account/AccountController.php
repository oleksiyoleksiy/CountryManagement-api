<?php

namespace App\Http\Controllers\Account;

use App\DTO\LoginDTO;
use App\DTO\RegistrationDTO;
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
        $data = $request->validated();

        $dto = new LoginDTO(...$data);

        return $this->service->login($dto);
    }

    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();

        $dto = new RegistrationDTO(...$data);

        return $this->service->register($dto);
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
