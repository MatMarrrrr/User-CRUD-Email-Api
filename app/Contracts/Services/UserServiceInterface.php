<?php

namespace App\Contracts\Services;

use Illuminate\Http\JsonResponse;

interface UserServiceInterface
{
    public function getAllUsers(): JsonResponse;
    public function getUserById(string $id): JsonResponse;
    public function createUser(array $data): JsonResponse;
    public function updateUser(string $id, array $data): JsonResponse;
    public function deleteUser(string $id): JsonResponse;
}
