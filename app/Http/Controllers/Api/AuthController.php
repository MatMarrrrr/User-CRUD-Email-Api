<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Contracts\Services\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected AuthServiceInterface $service;

    public function __construct(AuthServiceInterface $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->service->register($request->validated());
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->service->login($request->validated());
    }

    public function logout(): JsonResponse
    {
        return $this->service->logout();
    }
}
