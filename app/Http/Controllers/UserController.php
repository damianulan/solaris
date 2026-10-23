<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Nexus\Http\Controllers\BaseController;

final class UserController extends BaseController
{
    public function edit(User $user): Response
    {
        $availableLocales = config('app.available_locales', []);

        return Inertia::render('Profile/Edit', [
            'profile' => [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'middlename' => $user->middlename,
                'username' => $user->username,
                'email' => $user->email,
                'bio' => $user->bio,
                'lang' => $user->locale,
                'timezone' => $user->timezone,
                'avatarUrl' => $this->avatarUrl($user),
            ],
            'availableLocales' => is_array($availableLocales) ? $availableLocales : [],
            'availableTimezones' => timezone_identifiers_list(),
            'filepondUrl' => route('filepond-process'),
            'updateUrl' => route('users.update', $user),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user,
        UserService $userService,
    ): RedirectResponse {
        $userService->updateProfile($user, $request->profileAttributes());

        return back()->with('success', __('frontend.users.updated'));
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
