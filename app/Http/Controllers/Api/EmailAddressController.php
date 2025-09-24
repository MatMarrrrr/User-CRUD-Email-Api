<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailAddress;
use Illuminate\Http\Request;
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
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user->emails);
    }

    /**
     * Store a newly created email address for the user.
     */
    public function store(Request $request, string $userId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:email_addresses,email',
        ]);

        $email = $user->emails()->create($validated);

        return response()->json($email, Response::HTTP_CREATED);
    }

    /**
     * Display the specified email address.
     */
    public function show(string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $email = EmailAddress::find($emailId);

        if (! $email || $email->user_id !== $user->id) {
            return response()->json(['message' => 'Email not found for this user'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($email);
    }

    /**
     * Update the specified email address.
     */
    public function update(Request $request, string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $email = EmailAddress::find($emailId);

        if (! $email || $email->user_id !== $user->id) {
            return response()->json(['message' => 'Email not found for this user'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:email_addresses,email,' . $email->id,
        ]);

        $email->update($validated);

        return response()->json($email);
    }

    /**
     * Remove the specified email address.
     */
    public function destroy(string $userId, string $emailId): JsonResponse
    {
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $email = EmailAddress::find($emailId);

        if (! $email || $email->user_id !== $user->id) {
            return response()->json(['message' => 'Email not found for this user'], Response::HTTP_NOT_FOUND);
        }

        $email->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
