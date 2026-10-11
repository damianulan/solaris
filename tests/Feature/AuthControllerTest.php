<?php

declare(strict_types=1);

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use Tests\TestCase;

pest()->extend(TestCase::class);

it('renders each guest authentication form as an Inertia page', function (
    string $routeName,
    string $component,
    string $actionProp,
    string $actionRoute,
): void {
    $version = app(HandleInertiaRequests::class)->version(request());

    $this->withHeaders([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
    ])
        ->get(route($routeName))
        ->assertOk()
        ->assertJsonPath('component', $component)
        ->assertJsonPath("props.{$actionProp}", route($actionRoute));
})->with([
    'login' => ['login', 'Auth/Login', 'loginUrl', 'login.store'],
    'registration' => ['register', 'Auth/Register', 'registerUrl', 'register.store'],
    'forgot password' => ['password.request', 'Auth/ForgotPassword', 'passwordEmailUrl', 'password.email'],
]);

it('renders the reset password page with the token and email', function (): void {
    $version = app(HandleInertiaRequests::class)->version(request());

    $this->withHeaders([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
    ])
        ->get(route('password.reset', [
            'token' => 'reset-token',
            'email' => 'ada@example.com',
        ]))
        ->assertOk()
        ->assertJsonPath('component', 'Auth/ResetPassword')
        ->assertJsonPath('props.token', 'reset-token')
        ->assertJsonPath('props.email', 'ada@example.com')
        ->assertJsonPath('props.resetPasswordUrl', route('password.store'));
});

it('renders the password confirmation page for an authenticated user', function (): void {
    $user = User::factory()->make();
    $version = app(HandleInertiaRequests::class)->version(request());

    $this->actingAs($user)
        ->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
        ])
        ->get(route('password.confirm'))
        ->assertOk()
        ->assertJsonPath('component', 'Auth/ConfirmPassword')
        ->assertJsonPath('props.confirmPasswordUrl', route('password.confirm.store'));
});
