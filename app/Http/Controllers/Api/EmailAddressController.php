<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailAddressRequest;
use App\Http\Requests\UpdateEmailAddressRequest;
use App\Contracts\Services\EmailAddressServiceInterface;
use Illuminate\Http\JsonResponse;

class EmailAddressController extends Controller
{
    protected EmailAddressServiceInterface $service;

    public function __construct(EmailAddressServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the user's email addresses.
     */
    public function index(string $userId): JsonResponse
    {
        return $this->service->getUserEmails($userId);
    }

    /**
     * Store a newly created email address for the user.
     */
    public function store(StoreEmailAddressRequest $request, string $userId): JsonResponse
    {
       return $this->service->createEmail($userId, $request->validated());
    }

    /**
     * Display the specified email address.
     */
    public function show(string $userId, string $emailId): JsonResponse
    {
        return $this->service->getEmail($userId, $emailId);
    }

    /**
     * Update the specified email address.
     */
    public function update(UpdateEmailAddressRequest $request, string $userId, string $emailId): JsonResponse
    {
        return $this->service->updateEmail($userId, $emailId, $request->validated());
    }

    /**
     * Remove the specified email address.
     */
    public function destroy(string $userId, string $emailId): JsonResponse
    {
        return $this->service->deleteEmail($userId, $emailId);
    }
}
