<?php

namespace App\Contracts\Repositories;

use App\Models\EmailAddress;
use Illuminate\Support\Collection;

interface EmailAddressRepositoryInterface
{
    public function getByUserId(string $userId): Collection;
    public function findById(string $id): ?EmailAddress;
    public function createForUser(string $userId, array $data): EmailAddress;
    public function update(EmailAddress $email, array $data): EmailAddress;
    public function delete(EmailAddress $email): bool;
}
