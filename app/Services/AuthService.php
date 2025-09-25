<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function register(array $data): JsonResponse
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Registered successfully',
            'data'    => $user,
            'token'   => $token,
        ], Response::HTTP_CREATED);
    }

    public function login(array $credentials): JsonResponse
    {
        $user = \App\Models\User::where('login', $credentials['login'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged in successfully',
            'data'    => $user,
            'token'   => $token,
        ], Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully',
        ], Response::HTTP_OK);
    }
}
