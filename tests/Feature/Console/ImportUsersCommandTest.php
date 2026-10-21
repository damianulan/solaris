<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class);

it('imports users returned by the users API', function () {
    config()->set([
        'services.users_api.url' => 'https://users.example.test',
        'services.users_api.token' => 'api-token',
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'https://users.example.test/api/users' => Http::response([
            'data' => [
                [
                    'firstname' => 'Ada',
                    'middlename' => null,
                    'lastname' => 'Lovelace',
                    'email' => 'ada@example.test',
                    'username' => 'ada',
                ],
                [
                    'firstname' => 'Grace',
                    'middlename' => 'Brewster',
                    'lastname' => 'Hopper',
                    'email' => 'grace@example.test',
                    'username' => 'grace',
                ],
            ],
        ]),
    ]);

    $this->artisan('users:import')
        ->expectsOutputToContain('Imported 2 users.')
        ->assertSuccessful();

    expect(User::query()->count())->toBe(2);

    $this->assertDatabaseHas(User::TABLE, [
        'firstname' => 'Ada',
        'middlename' => null,
        'lastname' => 'Lovelace',
        'email' => 'ada@example.test',
        'username' => 'ada',
    ]);

    $this->assertDatabaseHas(User::TABLE, [
        'firstname' => 'Grace',
        'middlename' => 'Brewster',
        'lastname' => 'Hopper',
        'email' => 'grace@example.test',
        'username' => 'grace',
    ]);

    Http::assertSentCount(1);
});
