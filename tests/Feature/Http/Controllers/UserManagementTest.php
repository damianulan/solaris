<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class);

it('renders the selected user edit form', function () {
    $actor = User::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($actor)->get(route('users.edit', $user));

    $response->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
            ->component('Profile/Edit')
            ->where('profile.firstname', $user->firstname)
            ->where('profile.lastname', $user->lastname)
            ->where('updateUrl', route('users.update', $user))
            ->etc());
});

it('updates the selected user', function () {
    $actor = User::factory()->create();
    $user = User::factory()->create();
    $editUrl = route('users.edit', $user);

    $response = $this->actingAs($actor)
        ->from($editUrl)
        ->put(route('users.update', $user), [
            'firstname' => 'Updated',
            'lastname' => 'User',
            'middlename' => null,
            'username' => 'updated-user',
            'email' => 'updated@example.test',
            'bio' => 'Updated bio',
            'lang' => 'pl',
            'timezone' => 'Europe/Warsaw',
        ]);

    $response->assertRedirect($editUrl);

    $user->refresh();

    expect($user->firstname)->toBe('Updated');
    expect($user->lastname)->toBe('User');
    expect($user->locale)->toBe('pl');
});

it('suspends another user', function () {
    $actor = User::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($actor)
        ->from(route('home'))
        ->post(route('users.suspensions.store', $user));

    $response->assertRedirect(route('home'));

    expect($user->fresh()->suspended_at)->not->toBeNull();
});

it('forbids users from suspending their own account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('users.suspensions.store', $user));

    $response->assertForbidden();
    expect($user->fresh()->suspended_at)->toBeNull();
});
