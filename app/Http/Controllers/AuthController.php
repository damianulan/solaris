<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Nexus\Http\Controllers\AuthController as NexusAuthController;

final class AuthController extends NexusAuthController
{
    public function loginPage(): Response
    {
        return Inertia::render('Auth/Login', [
            'homeUrl' => route('home'),
            'loginUrl' => route('login.store'),
        ]);
    }
}
