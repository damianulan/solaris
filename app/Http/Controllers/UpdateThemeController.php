<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\ResolveThemeVariant;
use App\Http\Requests\UpdateThemeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Nexus\Http\Controllers\BaseController;

final class UpdateThemeController extends BaseController
{
    public function __invoke(UpdateThemeRequest $request): RedirectResponse
    {
        $variant = $request->themeVariant();

        $request->session()->put(ResolveThemeVariant::SESSION_KEY, $variant->value);

        $user = $request->user();

        if ($user instanceof User) {
            $user->updateQuietly(['theme' => $variant]);
        }

        return back();
    }
}
