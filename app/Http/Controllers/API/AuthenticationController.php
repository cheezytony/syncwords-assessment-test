<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthenticationService;

class AuthenticationController extends Controller
{
    public function __construct(
        protected readonly AuthenticationService $authenticationService
    ) {
    }

    public function __invoke(LoginRequest $request): array
    {
        return $this->authenticationService->login($request->validated());
    }
}
