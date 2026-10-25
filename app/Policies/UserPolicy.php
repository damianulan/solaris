<?php

declare(strict_types=1);

namespace App\Policies;

use App\Authorization\PermissionRegistry;
use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasSystemPermission(PermissionRegistry::ViewUsers);
    }

    public function view(User $user, User $employee): bool
    {
        return $user->hasSystemPermission(PermissionRegistry::ViewUsers)
            || $user->canManage($employee, PermissionRegistry::ViewUsers);
    }

    public function update(User $user, User $employee): bool
    {
        return $user->hasSystemPermission(PermissionRegistry::UpdateUsers)
            || $user->canManage($employee, PermissionRegistry::UpdateUsers);
    }

    public function delete(User $user, User $employee): bool
    {
        return ! $user->is($employee)
            && $user->hasSystemPermission(PermissionRegistry::DeleteUsers);
    }

    public function suspend(User $user, User $employee): bool
    {
        return ! $user->is($employee)
            && $user->hasSystemPermission(PermissionRegistry::SuspendUsers);
    }
}
