<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Nexus\Http\Controllers\AuthController as NexusAuthController;

final class AuthController extends NexusAuthController
{
    public function loginForm(): Response
    {
        return $this->renderPage('Auth/Login', [
            'homeUrl' => route('home'),
            'loginUrl' => route('login.store'),
            'passwordRequestUrl' => route('password.request'),
            'registerUrl' => route('register'),
        ]);
    }

    public function forgotPasswordForm(): Response
    {
        return $this->renderPage('Auth/ForgotPassword', [
            'loginUrl' => route('login'),
            'passwordEmailUrl' => route('password.email'),
        ]);
    }

    public function registerForm(): Response
    {
        return $this->renderPage('Auth/Register', [
            'homeUrl' => route('home'),
            'loginUrl' => route('login'),
            'registerUrl' => route('register.store'),
        ]);
    }

    public function confirmPasswordForm(): Response
    {
        return $this->renderPage('Auth/ConfirmPassword', [
            'confirmPasswordUrl' => route('password.confirm.store'),
            'homeUrl' => route('home'),
        ]);
    }

    public function resetPasswordPage(Request $request, string $token): Response
    {
        return $this->renderPage('Auth/ResetPassword', [
            'email' => $request->string('email')->toString(),
            'loginUrl' => route('login'),
            'resetPasswordUrl' => route('password.store'),
            'token' => $token,
        ]);
    }

    /** @param array<string, mixed> $props */
    private function renderPage(string $component, array $props = []): Response
    {
        $response = Inertia::render($component, $props)->toResponse(request());

        return new Response(
            $response->getContent(),
            $response->getStatusCode(),
            $response->headers->all(),
        );
    }
}
