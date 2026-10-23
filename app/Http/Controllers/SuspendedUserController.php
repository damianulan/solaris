<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SuspendUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Nexus\Http\Controllers\BaseController;

final class SuspendedUserController extends BaseController
{
    public function store(
        SuspendUserRequest $request,
        User $user,
        UserService $userService,
    ): RedirectResponse {
        $userService->suspend($user);

        return back()->with('success', __('frontend.users.suspended'));
    }
}
