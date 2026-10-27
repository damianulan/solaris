<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Contracts\Events\ShouldBeDiscovered;
use Nexus\Auth\Events\AuthenticatedFromClient;
use App\Models\User;

class AuthenticatedFromClientListener implements ShouldBeDiscovered
{
    public function handle(AuthenticatedFromClient $event): void
    {
        $rawUser = $event->rvcUser;
        /**
         * @var User $user
         */
        $user = $event->user;

        if (isset($rawUser['roles']) && is_array($rawUser['roles'])) {
            foreach ($rawUser['roles'] as $role) {
                if (!$user->hasRole($role)) {
                    $user->assignRole($role);
                }
            }
        }
    }

    public static function shouldBeDiscovered(): bool
    {
        return false;
    }
}
