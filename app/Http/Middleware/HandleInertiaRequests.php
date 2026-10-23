<?php

namespace App\Http\Middleware;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Nexus\Http\Middleware\Inertia\InertiaMiddleware;

class HandleInertiaRequests extends InertiaMiddleware
{
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth.user' => fn (): ?array => $this->authenticatedUser($request),
            'banner' => fn (): array | string | null => $this->banner($request),
        ];
    }

    /** @return array<string, string|null>|string|null */
    private function banner(Request $request): array | string | null
    {
        $banner = $request->session()->get('banner');

        if (! is_array($banner) && ! is_string($banner)) {
            return null;
        }

        return $banner;
    }

    private function authenticatedUser(Request $request): ?array
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return null;
        }

        return AuthResource::make($user)->toArray($request);
    }
}
