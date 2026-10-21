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
