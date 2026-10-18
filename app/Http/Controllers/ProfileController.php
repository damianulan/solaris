<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Nexus\Http\Controllers\BaseController;

final class ProfileController extends BaseController
{
    public function edit(Request $request): Response
    {
        $user = $this->authenticatedUser($request);
        $availableLocales = config('app.available_locales', []);

        return Inertia::render('Profile/Edit', [
            'profile' => [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'middlename' => $user->middlename,
                'username' => $user->username,
                'email' => $user->email,
                'bio' => $user->bio,
                'lang' => $user->lang,
                'timezone' => $user->timezone,
                'avatarUrl' => $this->avatarUrl($user),
            ],
            'availableLocales' => is_array($availableLocales) ? $availableLocales : [],
            'availableTimezones' => timezone_identifiers_list(),
            'filepondUrl' => route('filepond-process'),
            'updateUrl' => route('profile.update'),
        ]);
    }

    public function update(UpdateProfileRequest $request, UserService $userService): RedirectResponse
    {
        $userService->updateProfile(
            $this->authenticatedUser($request),
            $request->profileAttributes(),
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    private function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }

    private function avatarUrl(User $user): ?string
    {
        if ($user->avatar === null) {
            return null;
        }

        $diskName = config('filepond.disk', 'public');
        $disk = Storage::disk(is_string($diskName) ? $diskName : 'public');

        if (! $disk instanceof FilesystemAdapter) {
            return null;
        }

        return $disk->url($user->avatar);
    }
}
