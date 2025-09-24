<?php

namespace App\Services;

use App\Contracts\Repositories\EmailAddressRepositoryInterface;
use App\Contracts\Services\EmailAddressServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmailAddressService implements EmailAddressServiceInterface
{
    protected EmailAddressRepositoryInterface $repository;

    public function __construct(EmailAddressRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getUserEmails(string $userId): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $this->repository->getByUserId($userId),
        ], Response::HTTP_OK);
    }

    public function createEmail(string $userId, array $data): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = $this->repository->createForUser($userId, $data);

        return response()->json([
            'status' => 'success',
            'data'   => $email,
        ], Response::HTTP_CREATED);
    }

    public function getEmail(string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = $this->repository->findById($emailId);

        if (!$email || $email->user_id !== $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email not found for this user',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $email,
        ], Response::HTTP_OK);
    }

    public function updateEmail(string $userId, string $emailId, array $data): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = $this->repository->findById($emailId);
        if (!$email || $email->user_id !== $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email not found for this user',
            ], Response::HTTP_NOT_FOUND);
        }

        $updated = $this->repository->update($email, $data);

        return response()->json([
            'status' => 'success',
            'data'   => $updated,
        ], Response::HTTP_OK);
    }

    public function deleteEmail(string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = $this->repository->findById($emailId);
        if (!$email || $email->user_id !== $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email not found for this user',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->repository->delete($email);

        return response()->json([
            'status'  => 'success',
            'message' => 'Email deleted successfully',
        ], Response::HTTP_OK);
    }
}
