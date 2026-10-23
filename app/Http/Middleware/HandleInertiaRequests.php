<?php

namespace App\Http\Middleware;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Nexus\Http\Middleware\Inertia\InertiaMiddleware;

class HandleInertiaRequests extends InertiaMiddleware
{
    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
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

    /** @return array{id: int, name: string, email: string|null, avatarUrl: string|null, profileUrl: string, logoutUrl: string}|null */
    private function authenticatedUser(Request $request): ?array
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return null;
        }

        return AuthResource::make($user)->toArray($request);
    }
}
