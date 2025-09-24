<?php

namespace App\Contracts\Services;

use App\Models\EmailAddress;
use Illuminate\Http\JsonResponse;

interface EmailAddressServiceInterface
{
    public function getUserEmails(string $userId): JsonResponse;
    public function createEmail(string $userId, array $data): JsonResponse;
    public function getEmail(string $userId, string $emailId): JsonResponse;
    public function updateEmail(string $userId, string $emailId, array $data): JsonResponse;
    public function deleteEmail(string $userId, string $emailId): JsonResponse;
}
