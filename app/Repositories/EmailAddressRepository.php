<?php

namespace App\Repositories;

use App\Contracts\Repositories\EmailAddressRepositoryInterface;
use App\Models\EmailAddress;
use App\Models\User;
use Illuminate\Support\Collection;

class EmailAddressRepository implements EmailAddressRepositoryInterface
{
    public function getByUserId(string $userId): Collection
    {
        $user = User::find($userId);
        return $user ? $user->emails : collect();
    }

    public function findById(string $id): ?EmailAddress
    {
        return EmailAddress::find($id);
    }

    public function createForUser(string $userId, array $data): EmailAddress
    {
        $user = User::findOrFail($userId);
        return $user->emails()->create($data);
    }

    public function update(EmailAddress $email, array $data): EmailAddress
    {
        $email->update($data);
        return $email;
    }

    public function delete(EmailAddress $email): bool
    {
        return $email->delete();
    }
}
