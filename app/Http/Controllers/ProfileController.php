<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Nexus\Http\Controllers\BaseController;

final class ProfileController extends BaseController
{
    public function edit(): Response
    {
        return Inertia::render('Profile/Edit', [
            'profile' => [
                'name' => 'Taylor Otwell',
                'email' => 'taylor@example.com',
                'bio' => 'Building useful things for the web.',
                'birthDate' => '1985-09-12',
                'yearsOfExperience' => 15,
                'preferredContact' => 'email',
                'publicProfile' => true,
                'profileCompletion' => 75,
                'timezone' => 'Europe/Warsaw',
                'newsletter' => true,
            ],
            'updateUrl' => route('profile.update'),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $request->validated();

        return back();
    }
}
