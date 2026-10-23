<?php

declare(strict_types=1);

use App\Http\DataTables\Users\UsersDataTable;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class);

it('sorts sortable columns in the requested direction', function (string $direction, array $expectedNames) {
    User::query()->create([
        'uuid' => fake()->uuid(),
        'firstname' => 'Ada',
        'lastname' => 'Lovelace',
    ]);
    User::query()->create([
        'uuid' => fake()->uuid(),
        'firstname' => 'Grace',
        'lastname' => 'Hopper',
    ]);
    User::query()->create([
        'uuid' => fake()->uuid(),
        'firstname' => 'Alan',
        'lastname' => 'Turing',
    ]);

    $request = Request::create('/', 'GET', [
        'sortBy' => 'fullname',
        'sortDirection' => $direction,
    ]);

    $table = new UsersDataTable($request)->toArray();

    expect(array_column($table['paginator']['data'], 'fullname'))->toBe($expectedNames);
})->with([
    'ascending' => ['asc', ['Ada Lovelace', 'Alan Turing', 'Grace Hopper']],
    'descending' => ['desc', ['Grace Hopper', 'Alan Turing', 'Ada Lovelace']],
]);

it('serializes edit and suspend actions for each user', function () {
    $actor = User::factory()->create();
    $user = User::factory()->create();
    $request = Request::create('/');
    $request->setUserResolver(fn (): User => $actor);

    $table = new UsersDataTable($request)->toArray();
    $row = collect($table['paginator']['data'])->firstWhere('id', $user->id);

    expect($row)->toBeArray();
    expect($row['actions'])->toBe([
        [
            'color' => 'primary',
            'href' => route('users.edit', $user),
            'icon' => 'bi-pencil',
            'title' => __('frontend.data_table.actions.edit'),
            'variant' => 'text',
        ],
        [
            'color' => 'warning',
            'confirm' => __('frontend.data_table.actions.suspend_confirmation'),
            'disabled' => false,
            'href' => route('users.suspensions.store', $user),
            'icon' => 'bi-person-x',
            'method' => 'post',
            'title' => __('frontend.data_table.actions.suspend'),
            'variant' => 'text',
        ],
    ]);
});
