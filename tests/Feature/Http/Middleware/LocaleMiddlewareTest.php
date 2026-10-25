<?php

declare(strict_types=1);

use App\Http\Middleware\LocaleMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class);

it('renders requests using the authenticated user locale', function (): void {
    $user = User::factory()->createOne(['locale' => 'pl']);

    $response = $this->actingAs($user)->get('/');

    $response->assertSuccessful()->assertInertia(
        fn (AssertableInertia $page): AssertableInertia => $page
            ->where('localization.locale', 'pl'),
    );
});

it('quietly persists a valid requested locale for the authenticated user', function (): void {
    $user = User::factory()->createOne(['locale' => 'en']);
    $event = 'eloquent.updated: ' . User::class;
    Event::fake([$event]);

    $response = $this->actingAs($user)
        ->withHeader(LocaleMiddleware::HEADER_NAME, 'pl')
        ->get('/');

    $response->assertSuccessful()->assertInertia(
        fn (AssertableInertia $page): AssertableInertia => $page
            ->where('localization.locale', 'pl'),
    );
    expect($user->refresh()->locale)->toBe('pl');
    Event::assertNotDispatched($event);
});

it('ignores requested locales that are not available', function (): void {
    $user = User::factory()->createOne(['locale' => 'en']);

    $response = $this->actingAs($user)
        ->withHeader(LocaleMiddleware::HEADER_NAME, 'de')
        ->get('/');

    $response->assertSuccessful()->assertInertia(
        fn (AssertableInertia $page): AssertableInertia => $page
            ->where('localization.locale', 'en'),
    );
    expect($user->refresh()->locale)->toBe('en');
});
