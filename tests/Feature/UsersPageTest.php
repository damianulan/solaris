<?php

declare(strict_types=1);

use App\Authorization\RoleRegistry;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class);

function syncUserPageRoles(): void
{
    Artisan::call('sentinel:run');
}

it('redirects guests from the users page to sign in', function (): void {
    $this->get(route('users.index'))
        ->assertRedirectToRoute('login');
});

it('forbids users without permission from viewing the users page', function (): void {
    syncUserPageRoles();
    $user = User::factory()->createOne();
    $user->assignRole(RoleRegistry::User);

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

it('renders the users table and navigation item for authorized users', function (): void {
    syncUserPageRoles();
    $administrator = User::factory()->createOne();
    User::factory()->createOne();
    $administrator->assignRole(RoleRegistry::Administrator);

    $response = $this->actingAs($administrator)->get(route('users.index'));

    $response->assertInertia(fn (AssertableInertia $page): AssertableInertia => $page
        ->component('Users/Index', false)
        ->has('table.columns')
        ->has('table.paginator.data', 2)
        ->where('table.paginator.total', 2)
        ->where('sidebar.1.link', route('users.index'))
        ->where('sidebar.1.title', __('frontend.navigation.users')));
});
