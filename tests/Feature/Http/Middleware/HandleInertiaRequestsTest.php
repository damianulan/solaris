<?php

declare(strict_types=1);

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

pest()->extend(TestCase::class);

it('shares the authenticated user with Inertia pages', function (): void {
    $user = User::factory()->make([
        'id' => 42,
        'firstname' => 'Ada',
        'lastname' => 'Lovelace',
        'email' => 'ada@example.com',
    ]);
    $version = app(HandleInertiaRequests::class)->version(request());

    $this->actingAs($user)
        ->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
        ])
        ->get(route('home'))
        ->assertOk()
        ->assertJsonPath('props.auth.user', [
            'id' => 42,
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'avatarUrl' => null,
            'profileUrl' => route('profile.edit'),
        ])
        ->assertJsonMissingPath('props.auth.user.password')
        ->assertJsonMissingPath('props.auth.user.remember_token');
});

it('shares the authenticated user avatar URL', function (): void {
    $user = User::factory()->make([
        'avatar' => 'avatars/42/photo.jpg',
    ]);
    $version = app(HandleInertiaRequests::class)->version(request());

    $this->actingAs($user)
        ->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
        ])
        ->get(route('home'))
        ->assertOk()
        ->assertJsonPath(
            'props.auth.user.avatarUrl',
            Storage::disk('public')->url('avatars/42/photo.jpg'),
        );
});

it('shares a null user with guests', function (): void {
    $version = app(HandleInertiaRequests::class)->version(request());

    $this->withHeaders([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
    ])
        ->get(route('home'))
        ->assertOk()
        ->assertJsonPath('props.auth.user', null);
});
