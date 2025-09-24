<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailAddress;
use App\Http\Requests\StoreEmailAddressRequest;
use App\Http\Requests\UpdateEmailAddressRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmailAddressController extends Controller
{
    /**
     * Display a listing of the user's email addresses.
     */
    public function index(string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $user->emails,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created email address for the user.
     */
    public function store(StoreEmailAddressRequest $request, string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validated();

        $email = $user->emails()->create($validated);

        return response()->json([
            'status' => 'success',
            'data'   => $email,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified email address.
     */
    public function show(string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = EmailAddress::find($emailId);

        if (! $email || $email->user_id !== $user->id) {
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

    /**
     * Update the specified email address.
     */
    public function update(UpdateEmailAddressRequest $request, string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = EmailAddress::find($emailId);

        if (! $email || $email->user_id !== $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email not found for this user',
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validated();

        $email->update($validated);

        return response()->json([
            'status' => 'success',
            'data'   => $email,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified email address.
     */
    public function destroy(string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $email = EmailAddress::find($emailId);

        if (! $email || $email->user_id !== $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email not found for this user',
            ], Response::HTTP_NOT_FOUND);
        }

        $email->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Email deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
