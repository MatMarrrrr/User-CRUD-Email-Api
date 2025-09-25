<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function register(array $data): JsonResponse;
    public function login(array $credentials): JsonResponse;
    public function logout(): JsonResponse;
}
