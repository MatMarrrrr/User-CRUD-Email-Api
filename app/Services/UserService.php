<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers(): JsonResponse
    {
        $users = $this->repository->getAll();

        return response()->json([
            'status' => 'success',
            'data'   => $users,
        ], Response::HTTP_OK);
    }

    public function getUserById(string $id): JsonResponse
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $user,
        ], Response::HTTP_OK);
    }

    public function createUser(array $data): JsonResponse
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'User created successfully',
            'data'    => $user,
        ], Response::HTTP_CREATED);
    }

    public function updateUser(string $id, array $data): JsonResponse
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->repository->update($user, $data);

        return response()->json([
            'status'  => 'success',
            'message' => 'User updated successfully',
            'data'    => $user,
        ], Response::HTTP_OK);
    }

    public function deleteUser(string $id): JsonResponse
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->repository->delete($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'User deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
